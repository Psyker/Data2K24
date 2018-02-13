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
     * @ORM\Column(name="geo_point_2d", type="simple_array")
     */
    private $geoPoint2d;

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
     * @param array $geoPoint2d
     *
     * @return TouristicPlace
     */
    public function setGeoPoint2d($geoPoint2d)
    {
        $this->geoPoint2d = $geoPoint2d;

        return $this;
    }

    /**
     * Get geoPoint2d.
     *
     * @return array
     */
    public function getGeoPoint2d()
    {
        return $this->geoPoint2d;
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
}
