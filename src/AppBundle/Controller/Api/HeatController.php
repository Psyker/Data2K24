<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HeatController extends FOSRestController
{

    /**
     * @Rest\Get("/heat/{timestampStart}")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the entire heat map.",
     *     @SWG\Schema(
     *         type="string"
     *     )
     * )
     * @SWG\Tag(name="Heat Map")
     * @param int|null $timestampStart
     * @return JsonResponse
     * @internal param Request $request
     */
    public function getHeatMap(int $timestampStart = null)
    {

        if (empty($timestampStart)) {
            return new JsonResponse('timestampStart is required', 403);
        }
        @ini_set('memory_limit', -1);

        $dateline = $this->get('app.time_service')->getTimestamps();
        $frequencyIndex = $this->get('app.time_service')->getFrequencyByDates($timestampStart, $dateline);

        $em = $this->getDoctrine()->getManager();
        $payload = [
            'type' => 'FeatureCollection',
        ];
        $touristicPlaces = $em->getRepository('AppBundle:TouristicPlace')->findAll();
        $stations = $em->getRepository('AppBundle:Station')->findAll();
        $eventPlaces = $em->getRepository('AppBundle:EventPlace')->findAll();
        $livingPlaces = $em->getRepository('AppBundle:LivingPlace')->getFrequencyAndCoordinates();

        foreach ($touristicPlaces as $touristicPlace) {
            $payload['features'][] = [
                'properties' => [
                    'hint' => $touristicPlace->getFrequency()[$frequencyIndex]
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => $touristicPlace->getGeoPoint2d()
                ]
            ];
        }
        foreach ($stations as $station) {
            if ($station->getFrequency()) {
                $payload['features'][] = [
                    'properties' => [
                        'hint' => $station->getFrequency()[$frequencyIndex]
                    ],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => $station->getCoordinates()
                    ]
                ];
            }
        }
        foreach ($eventPlaces as $eventPlace) {
            $eventCount = count($em->getRepository('AppBundle:Event')->getEventsByDates($eventPlace, $timestampStart, $timestampStart + 7600));
            if ($eventCount !== 0) {
                $payload['features'][] = [
                    'properties' => [
                        'hint' => ($em->getRepository('AppBundle:Event')->getEventsByDates($eventPlace, $timestampStart, $timestampStart + 7600)[0])->getFiling() * $eventPlace->getCapacity()
                    ],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => $eventPlace->getGeoPoint()
                    ]
                ];
            }
        }
        foreach ($livingPlaces as $livingPlace) {
            $payload['features'][] = [
                'properties' => [
                    'hint' => $livingPlace['frequency'][$frequencyIndex]
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => $livingPlace['coordinates']
                ]
            ];
        }

        return new JsonResponse($payload);
    }
}
