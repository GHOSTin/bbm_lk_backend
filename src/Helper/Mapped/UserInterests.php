<?php


namespace App\Helper\Mapped;


use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class UserInterests
{
    protected static $studentInterests =
        [
            1 => "Волонтер",
            2 => "Победитель WorldSkills",
            3 => "Участник WorldSkills",
            4 => "Спортсмен",
            5 => "Староста группы",
            6 => "Физорг группы",
            7 => "Круглый отличник",
            8 => "Лидер по рейтингу",
            9 => "Лучший результат за практику"
        ];


    /**
     * @var integer
     * @Groups({"show"})
     */
    protected $id;

    /** @var string|null
     * @Groups({"show"})
     */
    protected $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    static function getStudentInterests()
    {
        return self::$studentInterests;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}