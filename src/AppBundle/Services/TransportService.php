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
                    $station->setFrequency($slots);
                }
            }
        }
        $this->em->flush();
        exit;
    }

    private function getComputedTraffic(Station $station, int $timestamp)
    {
        $traficPerDay = ($station->getStationTrafic()->getTrafic() / 365);
        $timestampHour = date('H', $timestamp);

        switch ($timestampHour) {
            case (2 > $timestampHour && $timestampHour < 4) :
                $traficPart = 0;
                break;
            case (4 > $timestampHour && $timestampHour < 6) :
                $traficPart = 1/24;
                break;
            case (6 > $timestampHour && $timestampHour < 8) :
                $traficPart = 6/24;
                break;
            case (8 > $timestampHour && $timestampHour < 10) :
                $traficPart = 3/24;
                break;
            case (10 > $timestampHour && $timestampHour < 12) :
                $traficPart = 1/24;
                break;
            case (12 > $timestampHour && $timestampHour < 16) :
                $traficPart = 2/24;
                break;
            case (16 > $timestampHour && $timestampHour < 18) :
                $traficPart = 5/24;
                break;
            case (18 > $timestampHour && $timestampHour < 20) :
                $traficPart = 3/24;
                break;
            case (20 > $timestampHour && $timestampHour < 0) :
                $traficPart = 2/24;
                break;
            default:
                $traficPart = 0;
                break;
        }

        return round($traficPerDay * $traficPart);
    }

}