<?php


namespace App\Service;


use App\Entity\LogImport;
use App\Entity\Teacher;
use App\Helper\Status\LogImportStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ImportService extends AbstractService
{
    /** @var Filesystem  */
    protected $filesystem;

    /**
     * @var string
     */
    protected $dirProject;

    /**
     * @var string
     */
    protected $pathTeachersCsvFolderNew;

    /**
     * @var string
     */
    protected $pathTeachersCsvFolderCompleted;

    /** @var string */
    protected $pathTeachersCsvFolderError;

    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $userPasswordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ContainerInterface $container,
        Filesystem $filesystem,
        UserPasswordEncoderInterface $userPasswordEncoder,
        $pathTeachersCsvFolderNew,
        $pathTeachersCsvFolderCompleted,
        $pathTeachersCsvFolderError
    )
    {
        parent::__construct($entityManager, $validator, $serializer);
        $this->container = $container;
        $this->filesystem = $filesystem;
        $this->dirProject = $this->container->get('kernel')->getProjectDir() . '/public';
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->pathTeachersCsvFolderCompleted = $this->dirProject . $pathTeachersCsvFolderCompleted;
        $this->pathTeachersCsvFolderNew = $this->dirProject . $pathTeachersCsvFolderNew;
        $this->pathTeachersCsvFolderError = $this->dirProject . $pathTeachersCsvFolderError;
    }

    public static function readCSVFile($path)
    {
        if (($fp = fopen($path, "r")) !== false) {
            $fileData = [];
            while (($row = fgetcsv($fp, 0, ';')) !== false) {
                $arr = array_map(function ($val) {
                    return iconv('CP1251', 'UTF-8', $val);
                }, $row);
                $fileData[] = $arr;
            }
            return $fileData;
        } else {
            return false;
        }
    }

    private function getTeachersArrayFromCsv($fileData)
    {
        $startRow = 1;
        $teacherRow = [
            'teacherNumber',
            'teacherLastName',
            'teacherFirstName',
            'teacherEmail',
        ];

        $teachersCollection = new ArrayCollection();
        $trashRow = [];
        foreach (array_slice($fileData, $startRow) as $value) {
            if (count($teacherRow) <= count($value)) {
                $row = [];
                foreach ($value as $i => $teacherAttribute) {
                    if (count($teacherRow) > $i)
                        $row[$teacherRow[$i]] = $teacherAttribute ? $teacherAttribute : null;
                }
                if ($row['teacherFirstName'] && $row['teacherLastName'] && $row['teacherEmail']) {
                    $teachersCollection->add($row);
                } else {
                    $trashRow[] = $value;
                }
            } else {
                $trashRow[] = $value;
            }
        }
        return $teachersCollection;
    }

    public function importTeachersFromCsv()
    {
        $this->checkAndCreateAllFolderForImport();
        $filesImportTeachers = $this->getFinderCsvFiles($this->pathTeachersCsvFolderNew);
        $importTeachersCollection = $this->readCollectionFromFinderCsv($filesImportTeachers, 'getTeachersArrayFromCsv',
            $this->pathTeachersCsvFolderError, LogImport::TYPE_TEACHER);

        $this->importFromCsv($importTeachersCollection, 'importTeacherFromCsv', $filesImportTeachers,
            $this->pathTeachersCsvFolderCompleted, $this->pathTeachersCsvFolderError, LogImport::TYPE_TEACHER);
    }

    private function readCollectionFromFinderCsv(Finder $finderFiles, $functionReadCsv, $importPathError, $typeFile)
    {
        $importCollection = new ArrayCollection();
        try {
            if ($finderFiles->hasResults()) {
                foreach ($finderFiles as $fileImport) {
                    $fileData = $this::readCSVFile($fileImport->getPathname());
                    /** @var ArrayCollection $fileImportCollection */
                    $fileImportCollection = $this->$functionReadCsv($fileData);
                    $importCollection = new ArrayCollection(array_merge($importCollection->toArray(), $fileImportCollection->toArray()));
                }
            }
        }
        catch (\Error $exception) {
            $this->errorHandlerImportFiles($finderFiles, $importPathError, $typeFile, $exception->getMessage());
        }
        return $importCollection;
    }

    private function importFromCsv(ArrayCollection $importCollection, $functionImportFileFromCsv, Finder $finderFiles, $importPathCompleted, $importPathError, $typeFile) {
        if (!$importCollection->isEmpty()) {
            try {
                $this->$functionImportFileFromCsv($importCollection);
                $this->saveCompletedImportFiles($finderFiles, $importPathCompleted, $typeFile);
            } catch (\Error $exception) {
                $this->errorHandlerImportFiles($finderFiles, $importPathError, $typeFile, $exception->getMessage());
            }
        }
    }

    private function checkAndCreateAllFolderForImport() {
        $this->checkAndCreateFolderForImport($this->pathTeachersCsvFolderNew);
        $this->checkAndCreateFolderForImport($this->pathTeachersCsvFolderCompleted);
        $this->checkAndCreateFolderForImport($this->pathTeachersCsvFolderError);
    }

    private function checkAndCreateFolderForImport($importPath) {
        if (!$this->filesystem->exists($importPath))
            $this->filesystem->mkdir($importPath);
    }

    private function getFinderCsvFiles($importPath) {
        $filesImport = new Finder();
        $filesImport->files()->in($importPath)->name('*.csv');
        return $filesImport;
    }

    private function saveCompletedImportFiles(Finder $finder, $pathCompleted, $typeFile) {
        $date = date("Y-m-d", time());
        foreach ($finder as $file) {
            $path = $pathCompleted . '/' . $date . '_' . $file->getFilename();
            $this->filesystem->rename($file->getPathname(), $path, true);
            $logImport = new LogImport();
            $logImport->setTypeFile($typeFile);
            $logImport->setFile($path);
            $logImport->setStatus(LogImportStatus::COMPLETED);
            $this->em->persist($logImport);
        }
        $this->em->flush();
    }

    private function errorHandlerImportFiles(Finder $finder, $pathError, $typeFile, $errorMessage) {
        $date = date("Y-m-d", time());
        foreach ($finder as $file) {
            $path = $pathError . '/' . $date . '_' . $file->getFilename();
            $this->filesystem->rename($file->getPathname(), $path, true);
            $logImport = new LogImport();
            $logImport->setTypeFile($typeFile);
            $logImport->setFile($path);
            $logImport->setErrorMessage($errorMessage);
            $logImport->setStatus(LogImportStatus::ERROR);
            $this->em->persist($logImport);
        }
        $this->em->flush();
    }

    /**
     * @param ArrayCollection $importTeachersCollection
     */
    private function importTeacherFromCsv(ArrayCollection $importTeachersCollection)
    {
        $userService = $this->container->get(UserService::class);
        $notificationService = $this->container->get(NotificationService::class);
        $allTeachers = $this->em->getRepository(Teacher::class)->getTeachersIndexByEmail();
        foreach ($importTeachersCollection as $importTeacher) {
            if (!$teacher = $allTeachers[$importTeacher['teacherEmail']] ?? null) {
                $teacher = $this->em->getRepository(Teacher::class)->findOneBy(['email' => $importTeacher['teacherEmail']]);
            }
            if (!$teacher) {
                $teacher = new Teacher();
                $teacher->setFirstName($importTeacher['teacherFirstName']);
                $teacher->setLastName($importTeacher['teacherLastName']);
                $teacher->setEmail($importTeacher['teacherEmail']);
                $password = $userService->generatePassword();
                $teacher->setPassword($this->userPasswordEncoder->encodePassword($teacher, $password));
                $this->em->persist($teacher);
                $this->em->flush();
                $notificationService->sendNotificationTeacherSignUpAction($teacher, $password);
            }
        }
    }
}