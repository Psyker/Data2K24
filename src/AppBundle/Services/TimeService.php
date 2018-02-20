<?php

namespace AppBundle\Services;

class TimeService
{
    const START_DATE = 1722556801;
    const TIME_SLOT = 2;
    const ACTIVITY_CODE = [
        'PARTY' => ['SA404', 'CH501'],
        'HOBBY' => [
            'CH103', 'CH104', 'CH102', 'CH105', 'CH106', 'CH201', 'CH401', 'CH402', 'CH403', 'CH303', 'CH107', 'CH108', 'CH501', 'CH109',
            'AE101',
            'SA408', 'SA205', 'SA405',
            'CE501', 'CE304', 'CE101', 'CE302', 'CE504', 'CE407',
            'CA115',
            'CB107', 'CB304', 'CB104', 'CB105', 'CB303', 'CE503',
            'AF101'
        ],
        'WORK' => [
            'AF102', 'AF104',
            'SA501', 'SA507', 'SA513',
            'SB201', 'SB203', 'SB101',
            'AC101', 'AC102',
            'CE102', 'CE101',
            'AD108', 'AD101', 'AD105', 'AD109',
            'CC103',
        ],
        'MOVE' => [
            'AD101', 'AD105',
            'CC301', 'CC103', 'CC201',
            'SB204',
            'CA104', 'CA109', 'CA301', 'CA106', 'CA302', 'CA101', 'CA103', 'CA111', 'CA202', 'CA112', 'CA203', 'CA101', 'CA110',
            'AF101',
            'CB102', 'CB101',
            'CD501',
            'SA201', 'SA206', 'SA194', 'SA102', 'SA305', 'SA405', 'SA101',
            'CE402', 'CE308', 'CE501', 'CE104', 'CE407', 'CE503', 'CE103',
            'CH402', 'CH202', 'CH302',
            'CF201', 'CF101', 'CF102',
            'SB202',
            'CG104', 'CG201', 'CG105',
            'AB103',
        ],
        'SLEEP' => [
            'CI105', 'CI104', 'CI103', 'CI102', 'CI101', 'CI201'
        ],
        'TOURISTIC' => [
            'CB304', 'CB103', 'CB104', 'CB105', 'CB202', 'CB301', 'CB101', 'CB108', 'CB302',
            'AB103',
            'SA202',
            'CE101', 'CE503', 'CE302',
            'CA205', 'CA101', 'CA106',
            'CH101',
        ],
        'EAT' => [
            'CH303', 'CH302', 'CH301', 'CH201', 'CH106', 'CH101', 'CH102', 'CH103', 'CH104', 'CH105', 'CH107', 'CH108', 'CH109', 'CH502', 'CH402', 'CH202',
            'CA103',
        ]
    ];
    const DISTRICT_PARTY_PATTERN = [
        0.2, 0.8, 0.8, 0.6, 0.8, 0.4, 0.4, 0.6, 0.4, 0.6, 0.6, 0.4, 0.8, 0.6, 0.6, 0.4, 1.0, 1.0, 0.6, 0.8, 1.0, 0.8, 0.6, 1.0, 0.4, 0.4, 0.2, 0.2, 0.8, 1.0, 0.6, 0.4, 0.8, 0.4, 0.4, 0.8, 0.6, 0.6, 0.4, 0.4, 0.6, 0.8, 0.4, 0.4, 0.2, 0.2, 0.4, 0.8, 0.2, 0.4, 0.6, 0.6, 0.4, 0.4, 0.4, 0.8, 0.4, 0.2, 0.2, 0.4, 0.2, 0.4, 0.2, 0.6, 0.6, 0.4, 0.6, 0.4, 0.8, 0.8, 0.4, 0.4, 0.4, 0.4, 0.2, 0.4, 0.4, 0.2, 0.2, 0.2
    ];
    const DISTRICT_HOME_PATTERN = [
        0.1, 0.2, 0.4, 0.2, 0.2, 0.4, 0.4, 0.4, 0.6, 0.4, 0.6, 0.4, 0.4, 0.2, 0.2, 0.4, 0.6, 0.6, 0.4, 0.4, 0.2, 0.1, 0.2, 0.4, 0.4, 0.1, 0.2, 0.6, 0.1, 0.1, 0.1, 0.1, 0.4, 0.2, 0.2, 0.6, 0.4, 0.4, 0.4, 0.8, 0.8, 1.0, 0.8, 0.6, 0.4, 0.6, 0.2, 0.4, 0.1, 0.2, 0.4, 0.6, 0.2, 0.4, 0.6, 0.8, 0.6, 0.6, 0.6, 0.4, 0.4, 0.6, 0.4, 0.2, 0.6, 0.6, 0.6, 0.8, 1.0, 1.0, 0.6, 0.8, 0.6, 0.4, 0.6, 0.8, 0.8, 0.6, 0.6, 0.6
    ];
    const DISTRICT_TOURISTIC_PATTERN = [
        1.0, 1.0, 0.8, 1.0, 1.0, 0.8, 0.6, 0.6, 0.6, 0.6, 0.8, 1.0, 1.0, 1.0, 0.8, 1.0, 0.8, 0.8, 0.6, 1.0, 0.8, 1.0, 0.4, 0.8, 0.6, 0.8, 0.6, 0.8, 1.0, 0.6, 0.4, 0.4, 0.6, 0.6, 0.8, 0.6, 0.4, 0.4, 0.6, 0.4, 0.6, 0.4, 0.4, 0.2, 0.1, 0.1, 0.8, 0.6, 0.1, 0.4, 0.6, 0.2, 0.8, 0.4, 0.1, 0.1, 0.1, 0.2, 0.4, 0.2, 0.1, 0.8, 0.6, 0.8, 0.2, 0.2, 0.8, 0.4, 0.6, 0.4, 0.2, 0.2, 0.4, 0.6, 0.4, 0.4, 0.4, 0.2, 0.6, 0.4
    ];

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
     * Return an array key.
     *
     * @param int $timestampStart
     * @param $dateline
     * @return int|string
     */
    public function getFrequencyByDates(int $timestampStart, $dateline)
    {
        /** @var \DateTime $date */
        foreach ($dateline as $key => $date) {
            if ($date->getTimestamp() === $timestampStart) {
                return $key;
            }
        }

        return null;
    }


    public function getAttractivenessByHour(int $timestamp, string $activityCode)
    {
        $hour = intval(date('H', $timestamp));
        $activityCode = substr($activityCode, 0, 5);
        $attractivenessIndex = 0.01;

        /** Bed time */
        if ($hour == 0 || $hour == 2 || $hour == 4 || $hour == 22 || $hour == 6) {
            if (in_array($activityCode, self::ACTIVITY_CODE['SLEEP'])) {
                $attractivenessIndex += 0.75;
            }
            if (in_array($activityCode, self::ACTIVITY_CODE['PARTY'])) {
                $attractivenessIndex += 0.5;
            }
        }
        /** Eat time */
        if ($hour == 12) {
            if (in_array($activityCode, self::ACTIVITY_CODE['EAT'])) {
                $attractivenessIndex += 1;
            }
        }

        /** Work time */
        if ($hour == 8 || $hour == 10 || $hour == 14 || $hour == 16
        ) {
            if (in_array($activityCode, self::ACTIVITY_CODE['WORK'])) {
                $attractivenessIndex += 0.5;
            }
        }

        /** Move time */
        if ($hour == 8 || $hour == 18) {
            if (in_array($activityCode, self::ACTIVITY_CODE['MOVE'])) {
                $attractivenessIndex += 0.75;
            }
        }

        /** Touristic shopping time */
        if ($hour == 10 || $hour == 14 || $hour == 16) {
            if (in_array($activityCode, self::ACTIVITY_CODE['TOURISTIC'])) {
                $attractivenessIndex += 0.5;
            }
            if (in_array($activityCode, self::ACTIVITY_CODE['EAT'])) {
                $attractivenessIndex += 0.25;
            }
        }

        /** Hobby time */
        if ($hour == 18 || $hour == 20) {
            if (in_array($activityCode, self::ACTIVITY_CODE['HOBBY'])) {
                $attractivenessIndex += 1;
            }
            if (in_array($activityCode, self::ACTIVITY_CODE['EAT'])) {
                $attractivenessIndex += 0.25;
            }
        }

        return $attractivenessIndex;
    }

    public function getDistrictPonderationByHour(int $timestamp, int $district)
    {
        $hour = intval(date('H', $timestamp));
        $result = 0;

        if ($hour == 0 || $hour == 2 || $hour == 4 || $hour == 6) {
            $result = self::DISTRICT_HOME_PATTERN[$district - 1];
        }

        if ($hour == 10 || $hour == 12 || $hour == 14 || $hour == 16 ) {
            $result = self::DISTRICT_TOURISTIC_PATTERN[$district - 1];
        }

        if ($hour == 20 || $hour == 18 || $hour == 22) {
            $result = self::DISTRICT_PARTY_PATTERN[$district - 1];
        }

        return $result;
    }
}
