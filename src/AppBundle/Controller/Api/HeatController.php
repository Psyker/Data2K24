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

        $dateline = $this->get('app.time_service')->getTimestamps();
        $frequencyIndex = $this->get('app.time_service')->getFrequencyByDates($timestampStart, $dateline);

        $em = $this->getDoctrine()->getManager();
        $payload = [
            'type' => 'FeatureCollection',
        ];
        $touristicPlaces = $em->getRepository('AppBundle:TouristicPlace')->findAll();
        $stations = $em->getRepository('AppBundle:Station')->findAll();

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

        return new JsonResponse($payload);
    }
}
