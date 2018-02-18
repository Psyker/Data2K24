<?php

namespace AppBundle\Services;


use AppBundle\Entity\Event;
use AppBundle\Entity\LivingPlace;
use Doctrine\ORM\EntityManager;
use Geokit\Math;

class LivingPlaceService
{

    private $entityManager;

    private $timeService;

    private $math;

    private $dateline;

    const START_DATE = 1722556801;
    const TIME_SLOT = 2;

    public function __construct(EntityManager $entityManager, TimeService $timeService)
    {
        $this->entityManager = $entityManager;
        $this->timeService = $timeService;
        $this->math = new Math();
        $this->dateline = $this->timeService->getTimestamps();
    }

    public function getFrequency()
    {
        @ini_set('memory_limit', -1);
        $index = 0;
        $chunkSize = 10;
        $dateline = $this->timeService->getTimestamps();
        $livingPlaces = $this->entityManager->getRepository(LivingPlace::class)->getInfos();
//        $stations = $this->entityManager->getRepository('AppBundle:Station')->getInfos();
//        $touristicPlaces = $this->entityManager->getRepository('AppBundle:TouristicPlace')->getInfos();
//        $eventPlaces = $this->entityManager->getRepository('AppBundle:EventPlace')->findAll();
        /** @var LivingPlace $livingPlace */
        foreach($livingPlaces as $key => $livingPlace) {
            $activityCode = $livingPlace['activityCode'];
            if (!$activityCode) {
                return;
            }
            $slots = [];
//            $capacityIndex = $this->computeCapacityIndex($livingPlace['area']);

            $area = $livingPlace['area'];
            $district = $livingPlace['district'];
            $situation = $livingPlace['situation'];
            $wayType = $livingPlace['wayType'];

            $potentialFrequency = ($area == 1) ? 30 : ($area == 2) ? 200 : 5000;
            $potentialFrequency *= ($wayType == 'RUE') ? 0.3 : ($wayType == 'BD' || $wayType == 'AV') ? 0.4 : ($situation == 'Concentration commerciale') ? 0.5 : 0.1;
            if ($situation == 'Concentration commerciale') {
                $potentialFrequency *= 0.5;
            } elseif ($wayType == 'RUE') {
                $potentialFrequency *= 0.3;
            } elseif ($wayType == 'BD' || $wayType == 'AV') {
                $potentialFrequency *= 0.4;
            } else {
                $potentialFrequency *= 0.1;
            }


            /** @var \DateTime $date */
            foreach ($dateline as $date) {
                $attractivityPonderedFrequency = $this->timeService->getAttractivenessByHour($date->getTimestamp(), $activityCode) * $potentialFrequency;
                $frequency = $attractivityPonderedFrequency * $this->timeService->getDistrictPonderationByHour($date->getTimestamp(), $district);
                $slots[] = $frequency;


//              $indexKey = $this->timeService->getFrequencyByDates($date->getTimestamp(), $this->dateline);
//              $transportFlow = $this->filterStationsClosest($livingPlace['coordinates'], $indexKey, $stations);
//              $touristicFlow = $this->filterTouristicPlacesClosest($livingPlace['coordinates'], $indexKey, $touristicPlaces);
//              $eventFlow = $this->filterEventsPlaceClosest($livingPlace['coordinates'], $date->getTimestamp(), $eventPlaces);
//              $frequency = ( ( ( (float) $transportFlow + (float) $touristicFlow + (float) $eventFlow) / 15)  * (float) $capacityIndex) * (float) $attractivenessIndex;

            }
            $this->entityManager->getRepository('AppBundle:LivingPlace')->find($livingPlace['id'])->setFrequency($slots);
            $index++;
            if (($index % $chunkSize) == 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }
        $this->entityManager->flush();
    }
    
    /*public function computeCapacityIndex(int $area)
    {

        $capacityIndex = ($area == 1) ? 0.25 : ($area == 2) ? 0.5 : ($area == 3) ? 1 : 0.5;

        return $capacityIndex;
    }*/

    /*function getDistance( $latitude1, $longitude1, $latitude2, $longitude2 )
    {
        $d = $this->math->distanceHaversine([$latitude1, $longitude1], [$latitude2, $longitude2])->meters();
        return $d;
    }*/

    /*private function filterStationsClosest($livingPlace, int $index, array $stations)
    {
        $frequency = 0;
        foreach ($stations as $station) {
            if (!empty($station['frequency'])) {
                list($stationLatitude, $stationLongitude) = [$station['coordinates'][0], $station['coordinates'][1]];
                list($livingLatitude, $livingLongitude) = [$livingPlace[0], $livingPlace[1]];
                $distance = $this->getDistance($livingLatitude, $livingLongitude, $stationLatitude, $stationLongitude);
                if ($distance <= 300) {
                    $frequency += $station['frequency'][$index];
                }
            }
        };

        return $frequency;
    }*/

    /*private function filterTouristicPlacesClosest($livingPlace, int $index, array $touristicPlaces)
    {
        $frequency = 0;
        foreach ($touristicPlaces as $touristicPlace) {
            list($touristicLatitude, $touristicLongitude) = [$touristicPlace['geoPoint2d'][0], $touristicPlace['geoPoint2d'][1]];
            list($livingLatitude, $livingLongitude) = [$livingPlace[0], $livingPlace[1]];
            $distance = $this->getDistance($livingLatitude, $livingLongitude, $touristicLatitude, $touristicLongitude);
            if ($distance <= 300) {
                $frequency += $touristicPlace['frequency'][$index];
            }
        };

        return $frequency;
    }*/

    /*private function filterEventsPlaceClosest($livingPlace, int $timestamp, array $eventPlaces)
    {
        $frequency = 0;
        foreach ($eventPlaces as $eventPlace) {
            list($eventPlaceLatitude, $eventPlaceLongitude) = [$eventPlace->getGeoPoint()[0], $eventPlace->getGeoPoint()[1]];
            list($livingLatitude, $livingLongitude) = [$livingPlace[0], $livingPlace[1]];
            $distance = $this->getDistance($livingLatitude, $livingLongitude, $eventPlaceLatitude, $eventPlaceLongitude);
            if ($distance <= 300) {
                /** @var Event $event
                $event = $this->entityManager->getRepository('AppBundle:Event')->getEventsByDates($eventPlace, $timestamp, $timestamp + 7600);
                $frequency += $event->getFiling() * $event->getEventPlace()->getCapacity();
            }

        };
        return $frequency;
    }*/
}