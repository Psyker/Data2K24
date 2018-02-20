<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TouristicPlace
 *
 * @ORM\Table(name="touristic_place")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TouristicPlaceRepository")
 */
class TouristicPlace
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
     * @var string
     *
     * @ORM\Column(name="place_name", type="string")
     */
    private $placeName;

    /**
     * @var array
     *
     * @ORM\Column(name="coordinates", type="simple_array")
     */
    private $coordinates;

    /**
     * @var int
     *
     * @ORM\Column(name="annual_frequency", type="integer")
     */
    private $annualFrequency;

    /**
     * @var array
     * @ORM\Column(nullable=true, type="simple_array", name="frequency")
     */
    private $frequency;

    /**
     * @var array
     * @ORM\Column(type="simple_array", name="hints", nullable=true)
     */
    private $hints;

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
     * Set placeName.
     *
     * @param string $placeName
     *
     * @return TouristicPlace
     */
    public function setPlaceName($placeName)
    {
        $this->placeName = $placeName;

        return $this;
    }

    /**
     * Get placeName.
     *
     * @return string
     */
    public function getPlaceName()
    {
        return $this->placeName;
    }

    /**
     * Set geoPoint2d.
     *
     * @param array $coordinates
     *
     * @return TouristicPlace
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * Get geoPoint2d.
     *
     * @return array
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set annualFrequency.
     *
     * @param int $annualFrequency
     *
     * @return TouristicPlace
     */
    public function setAnnualFrequency($annualFrequency)
    {
        $this->annualFrequency = $annualFrequency;

        return $this;
    }

    /**
     * Get annualFrequency.
     *
     * @return int
     */
    public function getAnnualFrequency()
    {
        return $this->annualFrequency;
    }

    /**
     * @param array $frequency
     * @return $this
     */
    public function setFrequency(array $frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * @return array
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @return array
     */
    public function getHints(): array
    {
        return $this->hints;
    }

    /**
     * @param array $hints
     * @return TouristicPlace
     */
    public function setHints(array $hints): TouristicPlace
    {
        $this->hints = $hints;

        return $this;
    }
}
