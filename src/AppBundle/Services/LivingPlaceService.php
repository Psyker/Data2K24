<?php

namespace AppBundle\Services;


use AppBundle\Entity\LivingPlace;
use Doctrine\ORM\EntityManager;

class LivingPlaceService
{

    private $entityManager;

    const START_DATE = 1722556801;
    const TIME_SLOT = 2;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFrequency()
    {
        $interval = new \DateInterval('PT'.self::TIME_SLOT.'H');
        $dateline = new \DatePeriod((new \DateTime())->setTimestamp(self::START_DATE), $interval, (new \DateTime())->setTimestamp(self::START_DATE)->modify('+16 day'));
        $livingPlaces = $this->entityManager->getRepository(LivingPlace::class)->findAll();
        /** @var LivingPlace $livingPlace */
        foreach($livingPlaces as $livingPlace) {
            $slots = [];
            /** @var \DateTime $date */
            foreach ($dateline as $date) {
                if (!empty($livingPlace->getArea())) {
                    $area = $livingPlace->getArea();
                    $capacityIndex = $this->computeCapacityIndex($area);
                    $codePlace = $livingPlace->getActivityCode();
                    
                }
            }
        }
        $this->entityManager->flush();
    }
    
    public function computeCapacityIndex(int $area)
    {
        $capacityIndex = null;
        
        switch ($area) {
            case 1 :
                $capacityIndex = 0.02;
                break;
            case 2 :
                $capacityIndex = 0.08;
                break;
            case 3:
                $capacityIndex = 0.8;
                break;
            default:
                break;
        }
        
        return $capacityIndex;
    }

    public function getAttractivenessIndex(float $capacityIndex)
    {
        switch ($capacityIndex) {

        }
    }

}