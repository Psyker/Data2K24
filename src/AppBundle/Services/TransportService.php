<?php

namespace AppBundle\Services;

use AppBundle\Entity\Station;
use Doctrine\ORM\EntityManager;

class TransportService
{

    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * @var TimeService $timeService
     */
    private $timeService;

    /**
     * TransportService constructor.
     * @param EntityManager $em
     * @param TimeService $timeService
     */
    public function __construct(EntityManager $em, TimeService $timeService)
    {
        $this->em = $em;
        $this->timeService = $timeService;
    }

    /**
     * Return hourly frequency by station.
     * @return void
     */
    public function getFrequency()
    {
        $dateline = $this->timeService->getTimestamps();
        $stations = $this->em->getRepository(Station::class)->findAll();
        /** @var Station $station */
        foreach($stations as $station) {
            $slots = [];
            /** @var \DateTime $date */
            foreach ($dateline as $date) {
                if (!empty($station->getStationTrafic())) {
                    $slots[] = $this->getComputedTraffic($station, $date->getTimestamp());
                }
            }
            $station->setFrequency($slots);
        }
        $this->em->flush();
    }

    /**
     * @param Station $station
     * @param int $timestamp
     * @return float
     */
    private function getComputedTraffic(Station $station, int $timestamp)
    {
        $trafficPerDay = ($station->getStationTrafic()->getTrafic() / 365);
        $timestampHour = intval(date('H', $timestamp));

        if ($timestampHour == 0 || $timestampHour == 2 || $timestampHour == 4) {
            $trafficPart = 0;
        }
        if ($timestampHour == 6) {
            $trafficPart = 1/24;
        }
        if ($timestampHour == 8) {
            $trafficPart = 6/24;
        }
        if ($timestampHour == 10) {
            $trafficPart = 3/24;
        }
        if ($timestampHour == 12) {
            $trafficPart = 1/24;
        }
        if ($timestampHour == 14 || $timestampHour == 16) {
            $trafficPart = 2/24;
        }
        if ($timestampHour == 18) {
            $trafficPart = 5/24;
        }
        if ($timestampHour == 20) {
            $trafficPart = 3/24;
        }
        if ($timestampHour == 22) {
            $trafficPart = 2/24;
        }

        return round($trafficPerDay * $trafficPart);
    }
}
