<?php

namespace AppBundle\Services;


use AppBundle\Entity\TouristicPlace;
use Doctrine\ORM\EntityManager;

class TouristicService
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
     * TouristicService constructor.
     * @param EntityManager $em
     * @param TimeService $timeService
     */
    public function __construct(EntityManager $em, TimeService $timeService)
    {
        $this->em = $em;
        $this->timeService = $timeService;
    }

    /**
     * Return hourly frequency by touristic place.
     * @return void
     */
    public function getFrequency()
    {
        $dateline = $this->timeService->getTimestamps();
        $touristicPlaces = $this->em->getRepository(TouristicPlace::class)->findAll();
        /** @var TouristicPlace $station */
        foreach($touristicPlaces as $place) {
            $slots = [];
            /** @var \DateTime $date */
            foreach ($dateline as $date) {
                $weight = $this->getWeight($date, $place);
                $tmp = date('H', $date->getTimestamp());
                $slots[] = intval($tmp) .'| ->'.  $this->getHourlyFrequency($place, $date->getTimestamp(), $weight);
            }
            $place->setFrequency($slots);
        }
        $this->em->flush();
    }

    /**
     * @param \DateTime $date
     * @param TouristicPlace $place
     * @return float|int
     */
    public function getWeight(\DateTime $date, TouristicPlace $place)
    {
        if ((date('D', $date->getTimestamp())) === 'Mon' && strpos($place->getPlaceName(), 'Musée') !== false) {
            $weightIndex = 0;
        } elseif ((date('D', $date->getTimestamp())) === 'Sat' && strpos($place->getPlaceName(), 'Musée') !== false) {
            $weightIndex = 1.50;
        } elseif (strpos($place->getPlaceName(), 'Musée') !== false) {
            $weightIndex = 1.35;
        } else {
            $weightIndex = 1;
        }

        return $weightIndex;
    }

    /**
     * @param TouristicPlace $place
     * @param int $timestamp
     * @param int $weight
     * @return float
     */
    public function getHourlyFrequency(TouristicPlace $place, int $timestamp, int $weight)
    {
        $dailyFrequency = ($place->getAnnualFrequency() / 365) * $weight;
        $timestampHour = intval(date('H', $timestamp));

        if (strpos($place->getPlaceName(), 'Musée') !== false) {
            if (2 == $timestampHour || $timestampHour == 4 || 6 == $timestampHour || 8 == $timestampHour) {
                $freqPart = 0;
            }
            if (10 == $timestampHour) {
                $freqPart = 3 / 12;
            }
            if (12 == $timestampHour || $timestampHour == 14) {
                $freqPart = 2 / 12;
            }
            if ($timestampHour == 16) {
                $freqPart = 3 / 12;
            }
            if (18 == $timestampHour || $timestampHour == 20) {
                $freqPart = 2 / 12;
            }
            if ($timestampHour == 22) {
                $freqPart = 2 / 12;
            }
            if ($timestampHour == 0) {
                $freqPart = 0;
            }
        } else {
            if ($timestampHour == 0 || $timestampHour == 6) {
                $freqPart = 0.75/24;
            }
            if ($timestampHour == 2 || $timestampHour == 4) {
                $freqPart = 0.25/24;
            }
            if ($timestampHour == 8) {
                $freqPart = 6/24;
            }
            if ($timestampHour == 10) {
                $freqPart = 4/24;
            }
            if ($timestampHour == 12) {
                $freqPart = 3/24;
            }
            if ($timestampHour == 14 || $timestampHour == 16 || $timestampHour == 18) {
                $freqPart = 3/24;
            }
            if ($timestampHour == 20) {
                $freqPart = 3/24;
            }
            if ($timestampHour == 22) {
                $freqPart = 1/24;
            }
        }

        return round($dailyFrequency * $freqPart);
    }
}
