<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DistrictRepository")
 */
class District
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var LivingPlace[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\LivingPlace", mappedBy="disctrictId")
     */
    private $livingPlaces;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, name="geo_shape_type")
     */
    private $geoShapeType;

    /**
     * @var array
     * @ORM\Column(type="json_array", name="geo_shape", nullable=false)
     */
    private $geoShape;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, name="typ_iris")
     */
    private $typIris;

    /**
     * @var float
     * @ORM\Column(type="float", name="p12_pop", nullable=true)
     */
    private $p12Pop;

    /**
     * @var float
     * @ORM\Column(name="dens_pop12", type="float", nullable=true)
     */
    private $densPop12;

    /**
     * @var float
     * @ORM\Column(name="pop12_h0014", type="float", nullable=true)
     */
    private $pop12H0014;

    /**
     * @var float
     * @ORM\Column(name="p12_h1529", type="float", nullable=true)
     */
    private $p12H1529;

    /**
     * @var float
     * @ORM\Column(name="p12_h3044", nullable=true, type="float")
     */
    private $p12H3044;

    /**
     * @var float
     * @ORM\Column(name="p12_h4559", nullable=true, type="float")
     */
    private $p12H4559;

    /**
     * @var float
     * @ORM\Column(name="p12_h6074", nullable=true, type="float")
     */
    private $p12H6074;

    /**
     * @var float
     * @ORM\Column(type="float", name="p12_h75p", nullable=true)
     */
    private $p12H75p;

    /**
     * @var float
     * @ORM\Column(name="p12_f0014", type="float", nullable=true)
     */
    private $p12F0014;

    /**
     * @var float
     * @ORM\Column(type="float", name="p12_f1529", nullable=false)
     */
    private $p12F1529;

    /**
     * @var float
     * @ORM\Column(type="float", name="p12_f3044", nullable=false)
     */
    private $p12F3044;

    /**
     * @var float
     * @ORM\Column(type="float", name="p12_f4559", nullable=false)
     */
    private $p12F4559;

    /**
     * @var float
     * @ORM\Column(type="float", name="p12_f6074", nullable=false)
     */
    private $p12F6074;

    /**
     * @var float
     * @ORM\Column(type="float", name="p12_f75p", nullable=false)
     */
    private $p12F75p;

    /**
     * @var float
     * @ORM\Column(type="float", name="p12_pop60", nullable=false)
     */
    private $p12Pop60;

    /**
     * @var float
     * @ORM\Column(type="float", name="p12_pop001", nullable=false)
     */
    private $p12Pop001;

    public function __construct()
    {
        $this->livingPlaces = new ArrayCollection();
    }

    /**
     * @return LivingPlace[]|ArrayCollection
     */
    public function getLivingPlaces()
    {
        return $this->livingPlaces;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getGeoShape(): array
    {
        return $this->geoShape;
    }

    /**
     * @param array $geoShape
     * @return District
     */
    public function setGeoShape(array $geoShape): District
    {
        $this->geoShape = $geoShape;

        return $this;
    }

    /**
     * @return string
     */
    public function getTypIris(): string
    {
        return $this->typIris;
    }

    /**
     * @param string $typIris
     * @return District
     */
    public function setTypIris(string $typIris): District
    {
        $this->typIris = $typIris;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12Pop(): float
    {
        return $this->p12Pop;
    }

    /**
     * @param float $p12Pop
     * @return District
     */
    public function setP12Pop(float $p12Pop): District
    {
        $this->p12Pop = $p12Pop;

        return $this;
    }

    /**
     * @return float
     */
    public function getDensPop12(): float
    {
        return $this->densPop12;
    }

    /**
     * @param float $densPop12
     * @return District
     */
    public function setDensPop12(float $densPop12): District
    {
        $this->densPop12 = $densPop12;

        return $this;
    }

    /**
     * @return float
     */
    public function getPop12H0014(): float
    {
        return $this->pop12H0014;
    }

    /**
     * @param float $pop12H0014
     * @return District
     */
    public function setPop12H0014(float $pop12H0014): District
    {
        $this->pop12H0014 = $pop12H0014;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12H1529(): float
    {
        return $this->p12H1529;
    }

    /**
     * @param float $p12H1529
     * @return District
     */
    public function setP12H1529(float $p12H1529): District
    {
        $this->p12H1529 = $p12H1529;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12H3044(): float
    {
        return $this->p12H3044;
    }

    /**
     * @param float $p12H3044
     * @return District
     */
    public function setP12H3044(float $p12H3044): District
    {
        $this->p12H3044 = $p12H3044;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12H4559(): float
    {
        return $this->p12H4559;
    }

    /**
     * @param float $p12H4559
     * @return District
     */
    public function setP12H4559(float $p12H4559): District
    {
        $this->p12H4559 = $p12H4559;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12H6074(): float
    {
        return $this->p12H6074;
    }

    /**
     * @param float $p12H6074
     * @return District
     */
    public function setP12H6074(float $p12H6074): District
    {
        $this->p12H6074 = $p12H6074;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12H75p(): float
    {
        return $this->p12H75p;
    }

    /**
     * @param float $p12H75p
     * @return District
     */
    public function setP12H75p(float $p12H75p): District
    {
        $this->p12H75p = $p12H75p;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12F0014(): float
    {
        return $this->p12F0014;
    }

    /**
     * @param float $p12F0014
     * @return District
     */
    public function setP12F0014(float $p12F0014): District
    {
        $this->p12F0014 = $p12F0014;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12F1529(): float
    {
        return $this->p12F1529;
    }

    /**
     * @param float $p12F1529
     * @return District
     */
    public function setP12F1529(float $p12F1529): District
    {
        $this->p12F1529 = $p12F1529;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12F3044(): float
    {
        return $this->p12F3044;
    }

    /**
     * @param float $p12F3044
     * @return District
     */
    public function setP12F3044(float $p12F3044): District
    {
        $this->p12F3044 = $p12F3044;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12F4559(): float
    {
        return $this->p12F4559;
    }

    /**
     * @param float $p12F4559
     * @return District
     */
    public function setP12F4559(float $p12F4559): District
    {
        $this->p12F4559 = $p12F4559;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12F6074(): float
    {
        return $this->p12F6074;
    }

    /**
     * @param float $p12F6074
     * @return District
     */
    public function setP12F6074(float $p12F6074): District
    {
        $this->p12F6074 = $p12F6074;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12F75p(): float
    {
        return $this->p12F75p;
    }

    /**
     * @param float $p12F75p
     * @return District
     */
    public function setP12F75p(float $p12F75p): District
    {
        $this->p12F75p = $p12F75p;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12Pop60(): float
    {
        return $this->p12Pop60;
    }

    /**
     * @param float $p12Pop60
     * @return District
     */
    public function setP12Pop60(float $p12Pop60): District
    {
        $this->p12Pop60 = $p12Pop60;

        return $this;
    }

    /**
     * @return float
     */
    public function getP12Pop001(): float
    {
        return $this->p12Pop001;
    }

    /**
     * @param float $p12Pop001
     * @return District
     */
    public function setP12Pop001(float $p12Pop001): District
    {
        $this->p12Pop001 = $p12Pop001;

        return $this;
    }

    /**
     * @return string
     */
    public function getGeoShapeType(): string
    {
        return $this->geoShapeType;
    }

    /**
     * @param string $geoShapeType
     * @return District
     */
    public function setGeoShapeType(string $geoShapeType): District
    {
        $this->geoShapeType = $geoShapeType;

        return $this;
    }
}
