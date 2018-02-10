<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

/**
 * Class ApiController
 * @package AppBundle\Controller
 */
class ApiController extends FOSRestController
{
    /**
     * @Rest\Get("/events/places")
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
        $payload = [];

        if (empty($eventPlaces))  {
            return new JsonResponse('Event Places not found', 404);
        }

        foreach ($eventPlaces as $key => $eventPlace) {
            $payload[$key] = [
                'name' => $eventPlace->getName(),
                'geo_point_2d' => $eventPlace->getGeoPoint(),
                'capacity' => $eventPlace->getCapacity(),
            ];
            /** @var Event $event */
            foreach ($eventPlace->getEvents() as $event) {
                $payload[$key]['events'] = [
                    'name' => $event->getName(),
                    'dates' => $event->getDates()
                ];
            }
        }

        return new JsonResponse($payload);
    }

    /**
     * @Rest\Get("/events/place/{id}")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the event place by id with their trials.",
     *     @SWG\Schema(
     *         type="string"
     *     )
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
            'name' => $eventPlace->getName(),
            'geo_point_2d' => $eventPlace->getGeoPoint(),
            'capacity' => $eventPlace->getCapacity(),
        ];
        /** @var Event $event */
        foreach ($eventPlace->getEvents() as $event) {
            $payload['events'] = [
                'name' => $event->getName(),
                'dates' => $event->getDates()
            ];
        }

        return new JsonResponse($payload);
    }
}
