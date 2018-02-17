<?php

namespace AppBundle\Services;

class TimeService
{
    const START_DATE = 1722556801;
    const TIME_SLOT = 2;

    /**
     * Returns an array of 2-hour periods on 16 days
     * @return \DatePeriod
     */
    public function getTimestamps()
    {
        $interval = new \DateInterval('PT'.self::TIME_SLOT.'H');
        $dateline = new \DatePeriod((new \DateTime())->setTimestamp(self::START_DATE), $interval, (new \DateTime())->setTimestamp(self::START_DATE)->modify('+16 day'));

        return $dateline;
    }

    /**
     * @param int $timestampStart
     * @return int|string
     */
    public function getFrequencyByDates(int $timestampStart)
    {
        $dateline = $this->getTimestamps();
        /** @var \DateTime $date */
        foreach ($dateline as $key => $date) {
            if ($date->getTimestamp() === $timestampStart) {
                return $key;
            }
        }

        return null;
    }
}
