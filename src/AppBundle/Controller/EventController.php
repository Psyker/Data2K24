<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventPlace;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

class EventController extends FOSRestController
{
    /**
     * @Rest\Get("/event/places")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the event places with their trials.",
     *     @SWG\Schema(
     *         type="string"
     *     )
     * )
     * @SWG\Tag(name="Event Place")
     */
    public function getEventPlaces()
    {
        $eventPlaces = $this->getDoctrine()->getRepository('AppBundle:EventPlace')->findAll();
        $payload = [
            'type' => 'FeatureCollection',
        ];

        if (empty($eventPlaces))  {
            return new JsonResponse('Event Places not found', 404);
        }

        foreach ($eventPlaces as $key => $eventPlace) {
            $payload['features'][$key] = [
                'type' => 'Feature',
                'properties' => [
                    'id' => $eventPlace->getId(),
                    'type' => 'event_place',
                    'name' => $eventPlace->getName(),
                    'geo_point_2d' => $eventPlace->getGeoPoint(),
                    'capacity' => $eventPlace->getCapacity(),
                ],
            ];
            $payload['features'][$key]['geometry'] = [
                'type' => 'Point',
                'coordinates' => $eventPlace->getGeoPoint(),
            ];
            /** @var Event $event */
            foreach ($eventPlace->getEvents() as $event) {
                $payload['features'][$key]['properties']['events'] = [
                    'id' => $event->getId(),
                    'type' => 'event',
                    'name' => $event->getName(),
                    'dates' => $event->getDates(),
                ];
            }
        }

        return new JsonResponse($payload);
    }

    /**
     * @Rest\Get("/event/place/{id}")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the event place by id with their trials.",
     *     @SWG\Schema(
     *         type="string"
     *     )
     * )
     * @SWG\Parameter(
     *     name="id",
     *     required=true,
     *     in="query",
     *     type="number",
     *     description="The id of the event place",
     * )
     * @SWG\Tag(name="Event Place")
     * @param int $id
     * @return JsonResponse
     */
    public function getEventPlaceBydId(int $id)
    {
        $eventPlace = $this->getDoctrine()->getRepository('AppBundle:EventPlace')->find($id);

        if (empty($eventPlace)) {
            return new JsonResponse('Event Place not found', 404);
        }

        $payload = [
            'type' => 'FeatureCollection',
            'features' => [
                [
                    'type' => 'Feature',
                    'properties' => [
                        'id' => $eventPlace->getId(),
                        'type' => 'event_place',
                        'name' => $eventPlace->getName(),
                        'geo_point_2d' => $eventPlace->getGeoPoint(),
                        'capacity' => $eventPlace->getCapacity(),
                    ],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => $eventPlace->getGeoPoint(),
                    ],
                ],
            ],
        ];
        /** @var Event $event */
        foreach ($eventPlace->getEvents() as $event) {
            $payload['features'][0]['properties']['events'] = [
                'id' => $event->getId(),
                'type' => 'event',
                'name' => $event->getName(),
                'dates' => $event->getDates(),
            ];
        }

        return new JsonResponse($payload);
    }

    /**
     * @Rest\Get("/event/place/{id}/events")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the events by event place or filtered by dates.",
     *     @SWG\Schema(
     *         type="string"
     *     )
     * )
     * @SWG\Parameter(
     *     name="timestampStart",
     *     required=false,
     *     in="query",
     *     type="number",
     *     description="The beginning of the events",
     * )
     * @SWG\Parameter(
     *     name="timestampEnd",
     *     required=false,
     *     in="query",
     *     type="number",
     *     description="The ending of the events",
     * )
     * @SWG\Tag(name="Event")
     * @ParamConverter("eventPlace", class="AppBundle:EventPlace")
     * @param Request $request
     * @param EventPlace $eventPlace
     * @return JsonResponse
     */
    public function getEventsByEventPlace(Request $request, EventPlace $eventPlace)
    {
        $request->get('timestampStart') ? $dateStart = (new \DateTime())->setTimestamp($request->get('timestampStart', null)) : $dateStart =  null;
        $request->get('timestampEnd') ? $dateEnd = (new \DateTime())->setTimestamp($request->get('timestampEnd', null)) : $dateEnd = null;

        $events = $this->getDoctrine()->getRepository('AppBundle:Event')
            ->getEventsByDates($eventPlace, $dateStart, $dateEnd);

        $payload = [];
        /** @var Event $event */
        foreach ($events as $event) (
            $payload[] = [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'timestampStart' => $event->getDates()[0],
                'timestampEnd' => $event->getDates()[1],
                'place_id' => $event->getEventPlace()->getId(),
                'place_name' => $event->getEventPlace()->getName(),
                'geo_point_2d' => $event->getEventPlace()->getGeoPoint()
            ]
        );

        return new JsonResponse($payload);
    }

    /**
     * @Rest\Get("/event/all")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all the events or filtered by dates.",
     *     @SWG\Schema(
     *         type="string"
     *     )
     * )
     * @SWG\Parameter(
     *     name="timestampStart",
     *     required=false,
     *     in="query",
     *     type="number",
     *     description="The beginning of the events",
     * )
     * @SWG\Parameter(
     *     name="timestampEnd",
     *     required=false,
     *     in="query",
     *     type="number",
     *     description="The ending of the events",
     * )
     * @SWG\Tag(name="Event")
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllEvents(Request $request)
    {
        $request->get('timestampStart') ? $dateStart = (new \DateTime())->setTimestamp($request->get('timestampStart', null)) : $dateStart =  null;
        $request->get('timestampEnd') ? $dateEnd = (new \DateTime())->setTimestamp($request->get('timestampEnd', null)) : $dateEnd = null;

        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->getAllEventsByDates($dateStart, $dateEnd);

        $payload = [];
        /** @var Event $event */
        foreach ($events as $event) (
            $payload[] = [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'timestampStart' => $event->getDates()[0],
                'timestampEnd' => $event->getDates()[1],
                'place_id' => $event->getEventPlace()->getId(),
                'place_name' => $event->getEventPlace()->getName(),
                'geo_point_2d' => $event->getEventPlace()->getGeoPoint()
            ]
        );


        return new JsonResponse($payload);
    }
}
