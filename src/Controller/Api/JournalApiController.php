<?php


namespace App\Controller\Api;


use App\Entity\AbstractUser;
use App\Entity\ParentStudent;
use App\Entity\Student;
use App\Filter\JournalFilter;
use App\Helper\Mapped\Journal;
use App\Service\JournalService;

use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class JournalApiController
 * @package App\Controller\Api
 */
class JournalApiController extends AbstractApiController
{
    /**
     * @SWG\Tag(name="Journal")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Journal",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="weekNumber", type="integer", example=37),
     *              @SWG\Property(property="date", type="string", example="2020-08-27T00:14:05.103Z"),
     *              @SWG\Property(property="journals", type="array",
     *                  @SWG\Items(
     *                      ref=@Model(type=Journal::class)
     *                  )
     *              )
     *          )
     *      )
     * )
     *
     * @SWG\Parameter(
     *   name="apikey",
     *   type="string",
     *   required=true,
     *   in="header",
     *   description="auth user's apikey"
     * )
     *
     * @SWG\Parameter(
     *   name="filter[dateStart]",
     *   type="string",
     *   required=true,
     *   in="query",
     *   description="Date in format ISO8601"
     * )
     *
     * @Route("/journal", name="api_journal", methods={"GET"})
     * @param Request $request
     * @param JournalService $journalService
     * @param DenormalizerInterface $denormalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function getJournalByStudent(
        Request $request,
        JournalService $journalService,
        DenormalizerInterface $denormalizer
    ) {
        /** @var AbstractUser $user */
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);

        if ($user instanceof ParentStudent) {
            $guid = $user->getStudentExternalId();
        } else {
            $guid = $user->getExternalGuid();
        }
        /** @var JournalFilter $filter */
        $filter = $denormalizer->denormalize($request->query->get('filter'), JournalFilter::class);

        $journals = $journalService->getJournal(
            $guid,
            $filter,
            $user->getTokenExternal()->getToken()
        );
        if (!empty($journals)) {
            $data['data']['weekNumber'] = $journals[0]->getWeekNumber();
            $data['data']['date'] = $journals[0]->getDate();
        }
        $data['data']['journals'] = $journals;
        $json = $this->serializer->serialize($data, "json");

        return $this->createResponse($json);
    }
}