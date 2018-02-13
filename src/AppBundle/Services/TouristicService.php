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

            }
        }
        $this->em->flush();
    }


    public function getWeight()
    {

    }
}