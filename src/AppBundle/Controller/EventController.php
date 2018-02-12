<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

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
     * @SWG\Tag(name="Data")
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
                'coordinates' => $eventPlace->getGeoPoint()
            ];
            /** @var Event $event */
            foreach ($eventPlace->getEvents() as $event) {
                $payload['features'][$key]['properties']['events'] = [
                    'id' => $event->getId(),
                    'type' => 'event',
                    'name' => $event->getName(),
                    'dates' => $event->getDates()
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
     * @SWG\Tag(name="Data")
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
                        'coordinates' => $eventPlace->getGeoPoint()
                    ]
                ]
            ]
        ];
        /** @var Event $event */
        foreach ($eventPlace->getEvents() as $event) {
            $payload['features'][0]['properties']['events'] = [
                'id' => $event->getId(),
                'type' => 'event',
                'name' => $event->getName(),
                'dates' => $event->getDates()
            ];
        }

        return new JsonResponse($payload);
    }

    public function getEventsByEventPlace()
    {


    }
}
