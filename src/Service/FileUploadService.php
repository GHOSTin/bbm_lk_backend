<?php


namespace App\Service;

use App\Helper\Exception\ApiExceptionHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;

class FileUploadService
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

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

    const BLOCKED_MIME = [
        'application/octet-stream',
        'application/x-bsh',
        'application/x-sh',
        'application/x-shar',
        'text/x-script.sh',
        'application/mac-binary',
        'application/macbinary',
        'application/octet-stream',
        'application/x-binary',
        'application/x-macbinary',
        'application/x-msdownload',
        'application/x-dosexec',
    ];

    public function __construct(
        SluggerInterface $slugger,
        Filesystem $fileSystem,
        ContainerInterface $container
    )
    {
        $this->slugger = $slugger;
        $this->fileSystem = $fileSystem;
        $this->container = $container;
        $this->dirPublic = $this->container->get('kernel')->getProjectDir() . '/public';
    }

    public function upload(UploadedFile $file, $targetDirectory) {
        $fullTargetDirectory = $this->dirPublic . $targetDirectory;
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        $filePath = $targetDirectory . $fileName;
        try {
            if (!$this->fileSystem->exists($fullTargetDirectory)) {
                $this->fileSystem->mkdir($fullTargetDirectory);
            }
            $file->move($fullTargetDirectory, $fileName);
        }
        catch (FileException $e) {
            ApiExceptionHandler::errorApiHandlerMessage('Error Upload File', $e->getMessage());
        }

        return $filePath;
    }

    public function deleteFile($fileName) {
        $fullFilePath = $this->dirPublic . $fileName;
        if ($this->fileSystem->exists($fullFilePath))
            $this->fileSystem->remove($fullFilePath);
        return true;
    }
}