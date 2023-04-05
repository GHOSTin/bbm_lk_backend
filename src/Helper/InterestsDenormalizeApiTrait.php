<?php


namespace App\Helper;

use App\Entity\Student;
use App\Helper\Mapped\UserInterests;
use App\Service\InterestsService;

trait InterestsDenormalizeApiTrait
{
    protected $interestsService;

    public function __construct(InterestsService $interestsService)
    {
        $this->interestsService = $interestsService;
    }

    /**
     * @param array $interestStringArrayFromRequest
     * @param Student|null $student
     * @return array
     */
    public function getStudentInterestsMapped(array $interestStringArrayFromRequest, ?Student $student): array
    {
        $studentInterestsArray = $this->interestsService->getStudentInterestsList();

        // TODO ВРЕМЕННО, ПОКА С ВНЕШНЕГО ПРИХОДЯТ НЕПРАВИЛЬНЫЕ (для тестирования мобилки)
        if($student && $student->getEmail()=='1@pmk-online.ru'){
            $interestStringArrayFromRequest = [
                'Волонтер',
                'Победитель WorldSkills',
                'Староста группы',
                'Лидер по рейтингу',
                'Спортсмен',
            ];
        }

        return $this->interestsService->getInterestsMapped($interestStringArrayFromRequest, $studentInterestsArray);
    }

}