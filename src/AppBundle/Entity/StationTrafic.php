<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StationTrafic
 *
 * @ORM\Table(name="station_trafic")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StationTraficRepository")
 */
class StationTrafic
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Station
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Station", mappedBy="station")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $station;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="city")
     */
    private $city;

    /**
     * @var int
     * @ORM\Column(type="integer", name="rank", nullable=true)
     */
    private $rank;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="network")
     */
    private $network;

    /**
     * @var int
     * @ORM\Column(type="integer", name="trafic", name="trafic", nullable=false)
     */
    private $trafic;

    /**
     * @var string
     * @ORM\Column(type="string", name="station_name", nullable=true)
     */
    private $stationName;

    /**
     * @var int
     * @ORM\Column(type="integer", name="arr", nullable=true)
     */
    private $arr;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return StationTrafic
     */
    public function setCity(string $city): StationTrafic
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     * @return StationTrafic
     */
    public function setRank(int $rank): StationTrafic
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * @return string
     */
    public function getNetwork(): string
    {
        return $this->network;
    }

    /**
     * @param string $network
     * @return StationTrafic
     */
    public function setNetwork(string $network): StationTrafic
    {
        $this->network = $network;

        return $this;
    }

    /**
     * @return int
     */
    public function getTrafic(): int
    {
        return $this->trafic;
    }

    /**
     * @param int $trafic
     * @return StationTrafic
     */
    public function setTrafic(int $trafic): StationTrafic
    {
        $this->trafic = $trafic;

        return $this;
    }

    /**
     * @return string
     */
    public function getStationName(): string
    {
        return $this->stationName;
    }

    /**
     * @param string $stationName
     * @return StationTrafic
     */
    public function setStation(string $stationName): StationTrafic
    {
        $this->stationName = $stationName;

        return $this;
    }

    /**
     * @return int
     */
    public function getArr(): int
    {
        return $this->arr;
    }

    /**
     * @param int $arr
     * @return StationTrafic
     */
    public function setArr(int $arr): StationTrafic
    {
        $this->arr = $arr;

        return $this;
    }

    /**
     * @return Station
     */
    public function getStation(): Station
    {
        return $this->station;
    }

    public function set($function, $value)
    {
        $this->$function($value);

        return $this;
    }
}
