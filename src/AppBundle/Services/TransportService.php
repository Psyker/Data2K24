<?php

namespace AppBundle\Services;

use AppBundle\Entity\Station;
use Doctrine\ORM\EntityManager;

class TransportService
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    const START_DATE = 1722556801;
    const TIME_SLOT = 2;

    public function getFrequency()
    {
        $interval = new \DateInterval('PT'.self::TIME_SLOT.'H');
        $dateline = new \DatePeriod((new \DateTime())->setTimestamp(self::START_DATE), $interval, (new \DateTime())->setTimestamp(self::START_DATE)->modify('+16 day'));
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

    private function getComputedTraffic(Station $station, int $timestamp)
    {
        $traficPerDay = ($station->getStationTrafic()->getTrafic() / 365);
        $timestampHour = intval(date('H', $timestamp));

        if ($timestampHour == 0 || $timestampHour == 2 || $timestampHour == 4) {
            $traficPart = 0;
        }
        if ($timestampHour == 6) {
            $traficPart = 1/24;
        }
        if ($timestampHour == 8) {
            $traficPart = 6/24;
        }
        if ($timestampHour == 10) {
            $traficPart = 3/24;
        }
        if ($timestampHour == 12) {
            $traficPart = 1/24;
        }
        if ($timestampHour == 14 || $timestampHour == 16) {
            $traficPart = 2/24;
        }
        if ($timestampHour == 18) {
            $traficPart = 5/24;
        }
        if ($timestampHour == 20) {
            $traficPart = 3/24;
        }
        if ($timestampHour == 22) {
            $traficPart = 2/24;
        }

        return round($traficPerDay * $traficPart);
    }

}