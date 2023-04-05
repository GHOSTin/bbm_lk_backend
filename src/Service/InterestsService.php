<?php


namespace App\Service;


use App\Helper\Mapped\News;
use App\Helper\Mapped\UserInterests;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class InterestsService
{
    public function getStudentInterestsList() {
        $interestsList = UserInterests::getStudentInterests();
        $interests = [];
        foreach ($interestsList as $key => $name) {
            $interests[] = new UserInterests($key, $name);
        }
        return $interests;
    }

    public function getInterestsMapped(
        array $interestsStringArrayFromRequest,
        array $constInterests
    )
    {
        $arrayCollectionConstInterests = new ArrayCollection($constInterests);
        $userInterests = [];
        foreach ($interestsStringArrayFromRequest as $key => $interestString)
        {
            $interest = $arrayCollectionConstInterests
                ->filter(function (UserInterests $userInterests) use ($interestString) {
                    return mb_strtolower($interestString) == mb_strtolower($userInterests->getName());
                })->first();
            if ($interest)
            {
                $userInterests[] = $interest;
            }
        }
        return $userInterests;
    }
}