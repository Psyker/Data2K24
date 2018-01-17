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
     * @var int
     * @ORM\Column(type="integer", nullable=true, name="arr")
     */
    private $arr;

    /**
     * @var string
     * @ORM\Column(type="string", name="address", nullable=true)
     */
    private $address;

    /**
     * @var array
     * @ORM\Column(type="simple_array", name="coordinates", nullable=true)
     */
    private $coordinates;

    /**
     * @var string
     * @ORM\Column(type="string", name="situation" ,nullable=true)
     */
    private $situation;

    /**
     * @var string
     * @ORM\Column(type="string", name="activity_code", nullable=true)
     */
    private $activityCode;

    /**
     * @var string
     * @ORM\Column(type="string", name="activity_label", nullable=true)
     */
    private $activityLabel;

    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=true, name="area")
     */
    private $area;

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
    public function getSituation(): string
    {
        return $this->situation;
    }

    /**
     * @param string $condition
     * @return LivingPlace
     */
    public function setSituation(string $condition): LivingPlace
    {
        $this->situation = $condition;

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

    public function set($function, $value)
    {
        $this->$function($value);

        return $this;
    }
}
