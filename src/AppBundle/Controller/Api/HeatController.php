<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventPlace;
use AppBundle\Entity\Station;
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
     *     name="timestampStart",
     *     required=true,
     *     in="path",
     *     type="number",
     *     description="the start date"
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
        $eventPlaces = $em->getRepository('AppBundle:EventPlace')->findAll();
        $livingPlaces = $em->getRepository('AppBundle:LivingPlace')->findPaginated($rows, $offset);


        foreach ($touristicPlaces as $touristicPlace) {
            $payload['features'][] = [
                'properties' => [
                    'hint' => $touristicPlace['hints'][$frequencyIndex],
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => $touristicPlace['coordinates'],
                ],
            ];
        }
        foreach ($stations as $station) {
            if ($station['hints']) {
                $payload['features'][] = [
                    'properties' => [
                        'hint' => $station['hints'][$frequencyIndex],
                    ],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => $station['coordinates'],
                    ],
                ];
            }
        }
        /** @var EventPlace $eventPlace */
        foreach ($eventPlaces as $key => $eventPlace) {
                /** @var Event $firstEvent */
                $payload['features'][] = [
                    'properties' => [
                        'hint' => $eventPlace->getHints()[$frequencyIndex],
                        'stations_closest' => []
                    ],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => $eventPlace->getGeoPoint(),
                    ],
                ];
                /** @var Station $station */
                foreach ($eventPlace->getStationsClosest() as $station) {
                    $payload['features'][$key]['properties']['stations_closest'][] = [
                        'line' => $station->getLineHint(),
                        'name' => $station->getName()
                    ];
                }
        }
        foreach ($livingPlaces as $livingPlace) {
            $payload['features'][] = [
                'properties' => [
                    'hint' => $livingPlace['hints'][$frequencyIndex],
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => $livingPlace['coordinates'],
                ],
            ];
        }

        return new JsonResponse($payload);
    }
}
