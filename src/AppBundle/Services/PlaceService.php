<?php

namespace AppBundle\Services;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventPlace;
use AppBundle\Entity\Station;
use Doctrine\ORM\EntityManager;
use Geokit\Math;

class PlaceService
{

    /** @var EntityManager $entityManager */
    private $entityManager;

    /** @var TimeService $timeService */
    private $timeService;

    private $math;

    public function __construct(EntityManager $entityManager, TimeService $timeService)
    {
        $this->entityManager = $entityManager;
        $this->timeService = $timeService;
        $this->math = new Math();
    }

    public function getFrequency()
    {
        $eventPlaces = $this->entityManager->getRepository(EventPlace::class)->findAll();
        $dateline = $this->timeService->getTimestamps();

        foreach ($eventPlaces as $eventPlace) {
            $slots = [];
            $capacity = $eventPlace->getCapacity();
            /** @var \DateTime $date */
            foreach ($dateline as $date) {
                $filings = [];
                $events = $this->entityManager->getRepository(Event::class)->getEventsByDates($eventPlace, $date->getTimeStamp(), $date->getTimestamp() + 7600);
                if (!empty($events)) {
                    $dayVariation = $this->getDayVariation(date('D', $date->getTimestamp()));
                    if (count($events) > 1) {
                        /** @var Event $event */
                        foreach ($events as $event) {
                            $filings[] = $event->getFiling();
                        }
                        $filingAvg = array_sum($filings) / count($events);
                        $slots[] = ($filingAvg * $capacity) * $dayVariation;
                    } else if (count($events) == 1) {
                        $slots[] = (((reset($events))->getFiling()) * $capacity) * $dayVariation;
                    }
                }
                $slots[] = 0;
            }
            $eventPlace->setFrequency($slots);
        }
        $this->entityManager->flush();
    }

    public function getDayVariation(string $day)
    {
        $variation = 0;

        switch ($day) {
            case 'Mon':
                $variation = 0.70;
                break;
            case 'Tue':
                $variation = 0.78;
                break;
            case 'Wed':
                $variation = 0.85;
                break;
            case 'Thu':
                $variation = 0.88;
                break;
            case 'Fri':
                $variation = 0.92;
                break;
            case 'Sat':
                $variation = 1;
                break;
            case 'Sun':
                $variation = 0.97;
                break;
            default:
                break;
        }

        return $variation;
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