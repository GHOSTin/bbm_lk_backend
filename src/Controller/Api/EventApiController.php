<?php

namespace App\Controller\Api;

use App\Entity\AbstractUser;
use App\Filter\EventFilter;
use App\Helper\Mapped\Event;
use App\Service\EventService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class EventApiController
 * @package  App\Controller\Api
 */
class EventApiController extends AbstractApiController
{
    /**
     *
     * @SWG\Tag(name="Event")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Events",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="events", type="array",
     *                  @SWG\Items(
     *                      ref=@Model(type=Event::class, groups={"index"})
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
     * @SWG\Parameter(
     *   name="filter[dateStart]",
     *   type="string",
     *   required=false,
     *   in="query",
     *   description="Date in format ISO8601"
     * )
     *
     * @SWG\Parameter(
     *   name="filter[pagination][page]",
     *   type="integer",
     *   required=false,
     *   in="query",
     *   description="Page (Default first page)"
     * )
     *
     * @SWG\Parameter(
     *   name="filter[pagination][per_page]",
     *   type="integer",
     *   required=false,
     *   in="query",
     *   description="Per Page (Default 10 elements)"
     * )
     *
     * @Route("/events", name="api_events_list", methods={"GET"})
     * @param Request $request
     * @param EventService $eventService
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function getEventsIndex(Request $request, EventService $eventService, DenormalizerInterface $denormalizer)
    {
        /** @var AbstractUser $user */
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);

        /** @var EventFilter $eventFilter */
        $eventFilter = $denormalizer->denormalize($request->query->get('filter'), EventFilter::class);

        $events = $eventService->getEvents($user, $eventFilter);
        if (!empty($events)) {
            $data['data']['events'] = $events;
        } else {
            $data['data']['events'] = [];
        }
        $json = $this->serializer->serialize($data, 'json', ['groups' => ['index']]);
        return $this->createResponse($json, Response::HTTP_OK);
    }

    /**
     *
     * @SWG\Tag(name="Event")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Event By Id",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="event", type="array",
     *                  @SWG\Items(
     *                      ref=@Model(type=Event::class, groups={"show"})
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
     * @Route("/events/{eventId}", name="api_event_show", methods={"GET"})
     * @param Request $request
     * @param EventService $eventService
     * @return Response
     */
    public function getEvent($eventId, Request $request, EventService $eventService)
    {
        /** @var AbstractUser $user */
        $user = $this->getUser();
        $this->requestValidatorService->checkAuthenticationUser($user);

        $event = $eventService->getEventById($eventId, $user->getTokenExternal()->getToken());
        if (!empty($event)) {
            $data['data']['event'] = $event;
        } else {
            $data['data']['event'] = [];
        }
        $json = $this->serializer->serialize($data, 'json', ['groups' => ['show']]);
        return $this->createResponse($json, Response::HTTP_OK);
    }
}
