<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StationRepository")
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
     * @var $trafic StationTrafic
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\StationTrafic", inversedBy="station")
     * @ORM\JoinColumn(nullable=true)
     */
    private $trafic;

    /**
     * @var int
     * @ORM\Column(type="integer", name="zip_code", nullable=true)
     */
    private $zipCode;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, name="description")
     */
    private $description;

    /**
     * @var array
     * @ORM\Column(type="simple_array" ,name="coordinates", nullable=false)
     */
    private $coordinates;

    /**
     * @var int
     * @ORM\Column(nullable=false, type="integer", name="departement")
     */
    private $departement;

    /**
     * @var int
     * @ORM\Column(type="integer", name="stop_id", nullable=false)
     */
    private $stopId;

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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Station
     */
    public function setDescription(string $description): Station
    {
        $this->description = $description;

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
    public function getDepartement(): int
    {
        return $this->departement;
    }

    /**
     * @param int $departement
     * @return Station
     */
    public function setDepartement(int $departement): Station
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * @return int
     */
    public function getStopId(): int
    {
        return $this->stopId;
    }

    /**
     * @param int $stopId
     * @return Station
     */
    public function setStopId(int $stopId): Station
    {
        $this->stopId = $stopId;

        return $this;
    }

    /**
     * @return int
     */
    public function getZipCode(): int
    {
        return $this->zipCode;
    }

    /**
     * @param int $zipCode
     * @return Station
     */
    public function setZipCode(int $zipCode): Station
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return StationTrafic
     */
    public function getTrafic(): StationTrafic
    {
        return $this->trafic;
    }

    /**
     * @param StationTrafic $trafic
     * @return Station
     */
    public function setTrafic(StationTrafic $trafic): Station
    {
        $this->trafic = $trafic;

        return $this;
    }

    public function set($function, $value)
    {
        $this->$function($value);

        return $this;
    }
}

