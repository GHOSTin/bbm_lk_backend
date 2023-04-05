<?php

namespace App\Controller\Api;

use App\Helper\Exception\ApiException;
use App\Helper\Exception\ApiExceptionHandler;
use App\Helper\Mapped\Lesson;
use App\Helper\Mapped\Schedule;
use App\Service\ProfileService;
use App\Service\ReferenceService;
use App\Service\ScheduleService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ScheduleApiController
 * @package  App\Controller\Api
 */
class ScheduleApiController extends AbstractApiController
{
    /**
     *
     * @SWG\Tag(name="Schedule")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Schedule",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="schedules", type="array",
     *                  @SWG\Items(
     *                      @SWG\Property(property="date", type="string", example="2020-08-27T00:14:05.103Z"),
     *                      @SWG\Property(property="week", type="integer", example=37),
     *                      @SWG\Property(property="group", type="string", example="ТМД-306"),
     *                      @SWG\Property(property="lessons", type="array",
     *                          @SWG\Items(
     *                              ref=@Model(type=Lesson::class, groups={"show"}),
     *                          )
     *                     )
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
     *   name="startDate",
     *   type="string",
     *   required=true,
     *   in="query",
     *   description="Date in format ISO8601"
     * )
     *
     * @SWG\Parameter(
     *   name="endDate",
     *   type="string",
     *   required=false,
     *   in="query",
     *   description="Date in format ISO8601"
     * )
     *
     * @SWG\Parameter(
     *   name="group",
     *   type="string",
     *   required=false,
     *   in="query",
     *   description="Group Name"
     * )
     *
     * @Route("/schedules", name="api_schedule_by_date", methods={"GET"})
     * @param Request $request
     * @param ScheduleService $scheduleService
     * @return Response
     */
    public function getSchedule(Request $request, ScheduleService $scheduleService)
    {
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);

        $startDate = $request->get('startDate', null);
        $endDate = $request->get('endDate', $startDate);
        $group = $request->get('group', null);
        $schedules = $scheduleService->getScheduleByDateAndGroup($startDate, $endDate, $group, $user);
        $data['data']['schedules'] = $schedules;
        $json = $this->serializer->serialize($data, 'json', ['api' => 'internal', 'user' => $user]);
        return $this->createResponse($json, Response::HTTP_OK);
    }
}
