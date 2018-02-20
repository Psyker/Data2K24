<?php

namespace AppBundle\Services;

use AppBundle\Entity\EventPlace;
use AppBundle\Entity\Station;
use Doctrine\ORM\EntityManager;
use Geokit\Math;

class PlaceService
{

    /** @var EntityManager $entityManager */
    private $entityManager;

    private $math;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->math = new Math();
    }

    public function getClosestStationsByEventPlace()
    {
        $eventPlaces = $this->entityManager->getRepository(EventPlace::class)->findAll();
        $stations = $this->entityManager->getRepository(Station::class)->findAll();

        /** @var EventPlace $eventPlace */
        foreach ($eventPlaces as $eventPlace) {
            $stationsClosest = [];
            list($pla, $plo) = $eventPlace->getGeoPoint();
            /** @var Station $station */
            foreach ($stations as $station) {
                list($sla, $slo) = $station->getCoordinates();
                if($this->getDistance($pla, $plo, $sla, $slo) <= 500) {
                    $stationsClosest[] = $station;
                }
            }
            $eventPlace->setStationsClosest($stationsClosest);
        }
        $this->entityManager->flush();
    }

    function getDistance($latitude1, $longitude1, $latitude2, $longitude2 )
    {
        $d = $this->math->distanceHaversine([$latitude1, $longitude1], [$latitude2, $longitude2])->meters();
        return $d;
    }
}