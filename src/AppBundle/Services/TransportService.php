<?php

namespace AppBundle\Services;

class TransportService
{

    const START_DATE = 1722556801;
    const TIME_SLOT = 2;

    public function getFrequency()
    {

        $interval = new \DateInterval('PT'.self::TIME_SLOT.'H');
        $dateline = new \DatePeriod((new \DateTime())->setTimestamp(self::START_DATE), $interval, (new \DateTime())->setTimestamp(self::START_DATE)->modify('+8 day'));
        foreach($dateline as $date) {
            dump($date);
        }

    }

}