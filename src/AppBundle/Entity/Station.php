<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StationRepository")
 * @ORM\Table(indexes={@Index(name="station_search_idx", columns={"line_hint"})})
 */
class Station
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string" ,name="name", nullable=false)
     */
    private $name;

    /**
     * @var $stationTrafic StationTrafic
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\StationTrafic", inversedBy="station")
     * @ORM\JoinColumn(nullable=true)
     */
    private $stationTrafic;

    /**
     * @var int
     * @ORM\Column(type="string", name="line_hint", nullable=true)
     */
    private $lineHint;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, name="network")
     */
    private $network;

    /**
     * @var array
     * @ORM\Column(type="simple_array" ,name="coordinates", nullable=false)
     */
    private $coordinates;

    /**
     * @var int
     * @ORM\Column(nullable=false, type="string", name="operator")
     */
    private $operator;

    /**
     * @var array
     * @ORM\Column(nullable=true, type="simple_array", name="frequency")
     */
    private $frequency;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Station
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Station
     */
    public function setName(string $name): Station
    {
        $this->name = $name;

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
     * @return Station
     */
    public function setNetwork(string $network): Station
    {
        $this->network = $network;

        return $this;
    }

    /**
     * @return array
     */
    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    /**
     * @param array $coordinates
     * @return Station
     */
    public function setCoordinates(array $coordinates): Station
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * @return int
     */
    public function getLineHint(): int
    {
        return $this->lineHint;
    }

    /**
     * @param string $lineHint
     * @return Station
     */
    public function setLineHint(string $lineHint): Station
    {
        $this->lineHint = $lineHint;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     * @return Station
     */
    public function setOperator(string $operator): Station
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * @return StationTrafic
     */
    public function getStationTrafic()
    {
        return $this->stationTrafic;
    }

    /**
     * @param StationTrafic $stationTrafic
     * @return Station
     */
    public function setStationTrafic(StationTrafic $stationTrafic): Station
    {
        $this->stationTrafic = $stationTrafic;

        return $this;
    }

    public function setFrequency(array $frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getFrequency()
    {
        return $this->frequency;
    }

    public function set($function, $value)
    {
        $this->$function($value);

        return $this;
    }
}

