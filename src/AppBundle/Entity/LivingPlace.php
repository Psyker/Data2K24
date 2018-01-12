<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LivingPlaceRepository")
 */
class LivingPlace
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var District
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\District", inversedBy="livingPlaces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $disctrict;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, name="arr")
     */
    private $arr;

    /**
     * @var string
     * @ORM\Column(type="string", name="address", nullable=false)
     */
    private $address;

    /**
     * @var array
     * @ORM\Column(type="simple_array", name="coordinates", nullable=false)
     */
    private $coordinates;

    /**
     * @var string
     * @ORM\Column(type="string", name="condition" ,nullable=false)
     */
    private $condition;

    /**
     * @var string
     * @ORM\Column(type="string", name="activity_code", nullable=false)
     */
    private $activityCode;

    /**
     * @var string
     * @ORM\Column(type="string", name="activity_label", nullable=false)
     */
    private $activityLabel;

    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=false, name="area")
     */
    private $area;

    /**
     * @var int
     * @ORM\Column(type="integer", name="ccid", nullable=false)
     */
    private $Ccid;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return LivingPlace
     */
    public function setId($id)
    {
        $this->id = $id;

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
     * @return LivingPlace
     */
    public function setArr(int $arr): LivingPlace
    {
        $this->arr = $arr;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return LivingPlace
     */
    public function setAddress(string $address): LivingPlace
    {
        $this->address = $address;

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
     * @return LivingPlace
     */
    public function setCoordinates(array $coordinates): LivingPlace
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     * @return LivingPlace
     */
    public function setCondition(string $condition): LivingPlace
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * @return string
     */
    public function getActivityCode(): string
    {
        return $this->activityCode;
    }

    /**
     * @param string $activityCode
     * @return LivingPlace
     */
    public function setActivityCode(string $activityCode): LivingPlace
    {
        $this->activityCode = $activityCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getActivityLabel(): string
    {
        return $this->activityLabel;
    }

    /**
     * @param string $activityLabel
     * @return LivingPlace
     */
    public function setActivityLabel(string $activityLabel): LivingPlace
    {
        $this->activityLabel = $activityLabel;

        return $this;
    }

    /**
     * @return int
     */
    public function getArea(): int
    {
        return $this->area;
    }

    /**
     * @param int $area
     * @return LivingPlace
     */
    public function setArea(int $area): LivingPlace
    {
        $this->area = $area;

        return $this;
    }

    /**
     * @return int
     */
    public function getCcid(): int
    {
        return $this->Ccid;
    }

    /**
     * @param int $Ccid
     * @return LivingPlace
     */
    public function setCcid(int $Ccid): LivingPlace
    {
        $this->Ccid = $Ccid;

        return $this;
    }

    public function setDistrict(District $district)
    {
        $this->disctrict = $district;
    }
}
