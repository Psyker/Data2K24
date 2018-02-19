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
     * @SWG\Parameter(
     *     name="rows",
     *     required=false,
     *     in="query",
     *     type="number",
     *     description="Number of rows",
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     required=false,
     *     in="query",
     *     type="number",
     *     description="The offset",
     * )
     * @SWG\Tag(name="Heat Map")
     * @param Request $request
     * @param int|null $timestampStart
     * @return JsonResponse
     * @internal param Request $request
     */
    public function getHeatMap(Request $request, int $timestampStart = null)
    {

        if (empty($timestampStart)) {
            return new JsonResponse('timestampStart is required', 403);
        }
        $rows = $request->get('rows', null);
        $offset = $request->get('offset', null);

        @ini_set('memory_limit', -1);

        $dateline = $this->get('app.time_service')->getTimestamps();
        $frequencyIndex = $this->get('app.time_service')->getFrequencyByDates($timestampStart, $dateline);

        $em = $this->getDoctrine()->getManager();
        $payload = [
            'type' => 'FeatureCollection',
        ];
        $touristicPlaces = $em->getRepository('AppBundle:TouristicPlace')->findPaginated($rows, $offset);
        $stations = $em->getRepository('AppBundle:Station')->findPaginated($rows, $offset);
        $eventPlaces = $em->getRepository('AppBundle:EventPlace')->findPaginated($rows, $offset);
        $livingPlaces = $em->getRepository('AppBundle:LivingPlace')->findPaginated($rows, $offset);

        foreach ($touristicPlaces as $touristicPlace) {
            $payload['features'][] = [
                'properties' => [
                    'hint' => $touristicPlace['frequency'][$frequencyIndex]
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => $touristicPlace['geoPoint2d']
                ]
            ];
        }
        foreach ($stations as $station) {
            if ($station['frequency']) {
                $payload['features'][] = [
                    'properties' => [
                        'hint' => $station['frequency'][$frequencyIndex]
                    ],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => $station['coordinates']
                    ]
                ];
            }
        }
        foreach ($eventPlaces as $eventPlace) {
            $eventCount = count($em->getRepository('AppBundle:Event')->getEventsByDates($eventPlace['id'], $timestampStart, $timestampStart + 7600));
            if ($eventCount !== 0) {
                $payload['features'][] = [
                    'properties' => [
                        'hint' => ($em->getRepository('AppBundle:Event')->getEventsByDates($eventPlace['id'], $timestampStart, $timestampStart + 7600)[0])->getFiling() * $eventPlace['capacity']
                    ],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => $eventPlace['geoPoint2d']
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
