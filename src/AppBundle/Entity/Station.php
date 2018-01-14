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
     * @ORM\Column(type="string", unique=true, nullable=false, name="record_id")
     */
    private $recordId;

    /**
     * @var string
     * @ORM\Column(type="string" ,name="name", nullable=false)
     */
    private $name;

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
     * @ORM\Column(type="integer", name="insee_code", nullable=false)
     */
    private $InseeCode;

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
    public function getInseeCode(): int
    {
        return $this->InseeCode;
    }

    /**
     * @param int $InseeCode
     * @return Station
     */
    public function setInseeCode(int $InseeCode): Station
    {
        $this->InseeCode = $InseeCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecordId(): string
    {
        return $this->recordId;
    }

    /**
     * @param string $recordId
     * @return Station
     */
    public function setRecordId(string $recordId): Station
    {
        $this->recordId = $recordId;

        return $this;
    }
}

