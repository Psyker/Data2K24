<?php

namespace AppBundle\Services;


use AppBundle\Entity\TouristicPlace;
use Doctrine\ORM\EntityManager;

class TouristicService
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
        $touristicPlaces = $this->em->getRepository(TouristicPlace::class)->findAll();
        /** @var TouristicPlace $station */
        foreach($touristicPlaces as $place) {
            $slots = [];
            /** @var \DateTime $date */
            foreach ($dateline as $date) {
                $weight = $this->getWeight($date, $place);
                $this->getHourlyFrequency($place->getAnnualFrequency(), $date->getTimestamp(), $weight);
            }
        }
        $this->em->flush();
    }


    public function getWeight(\DateTime $date, TouristicPlace $place)
    {
        if ((date('D', $date->getTimestamp())) === 'Mon' && strpos($place->getPlaceName(), 'Musée') !== false) {
            $weightIndex = 0;
        } else {
            $weightIndex = 1.36;
        }

        return $weightIndex;
    }

    public function getHourlyFrequency(TouristicPlace $place, int $timestamp, int $weight)
    {
        $dailyFrequency = ($place->getAnnualFrequency() / 365) * $weight;
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
    }
}