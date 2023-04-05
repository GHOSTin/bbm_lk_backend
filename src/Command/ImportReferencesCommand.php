<?php

namespace App\Command;

use App\Entity\Reference;
use App\Helper\Status\ReferenceStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ImportReferencesCommand extends Command
{
    protected static $defaultName = 'app:import:references';


    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $dirPublic;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        ContainerInterface $container,
        Filesystem $fileSystem,
        EntityManagerInterface $em
    )
    {
        $this->container = $container;
        $this->fileSystem = $fileSystem;
        $this->dirPublic = $this->container->get('kernel')->getProjectDir() . '/public';
        $this->em = $em;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $pathReferences = $this->container->getParameter('path_references_directory');
        $fullPathReferences = $this->dirPublic . $pathReferences;
        if ($this->fileSystem->exists($fullPathReferences)) {
            $finder = new Finder();
            $arrayFilesReferences = $finder->files()->in($fullPathReferences);
            if ($arrayFilesReferences->count() != 0) {
                $references = $this->em->getRepository(Reference::class)->getReferencesWithIndexByPathFile();
                /** @var SplFileInfo $fileReference */
                foreach ($arrayFilesReferences as $fileReference) {
                    $filePath = $pathReferences . $fileReference->getFilename();
                    /** @var Reference $reference */
                    $reference = $references[$filePath] ?? null;
                    if (!$reference) {
                        $reference = new Reference();
                        $reference->setPathFile($filePath);
                        $reference->setName($fileReference->getFilenameWithoutExtension());
                        $this->em->persist($reference);
                    } else {
                        $reference->setStatus(ReferenceStatus::ACTIVE);
                        unset($references[$filePath]);
                    }
                }
                /** @var Reference $reference */
                foreach ($references as $reference) {
                    $reference->setStatus(ReferenceStatus::INACTIVE);
                }
                $this->em->flush();
            }
        }

        $io->success('Success.');

        return 0;
    }
}
