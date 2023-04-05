<?php

namespace App\Entity;

use App\Helper\Status\StatusTrait;
use App\Repository\LogImportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogImportRepository::class)
 */
class LogImport
{
    use StatusTrait;

    const TYPE_TEACHER = 1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\Column(type="integer")
     */
    private $importAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $typeFile;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $errorMessage;

    public function __construct()
    {
        $this->importAt = time();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getImportAt(): ?int
    {
        return $this->importAt;
    }

    public function setImportAt(int $importAt): self
    {
        $this->importAt = $importAt;

        return $this;
    }

    public function getTypeFile(): ?int
    {
        return $this->typeFile;
    }

    public function setTypeFile(int $typeFile): self
    {
        $this->typeFile = $typeFile;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }
}
