<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * EventPlace
 *
 * @ORM\Table(name="event_place")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventPlaceRepository")
 */
class EventPlace
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
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="adress", type="string", nullable=true)
     */
    private $adress;

    /**
     * @var array
     * @ORM\Column(name="geo_point", type="simple_array", nullable=true)
     */
    private $geoPoint;

    /**
     * @var Event
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Event", mappedBy="eventPlace")
     */
    private $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return EventPlace
     */
    public function setName(string $name): EventPlace
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAdress(): string
    {
        return $this->adress;
    }

    /**
     * @param string $adress
     * @return EventPlace
     */
    public function setAdress(string $adress): EventPlace
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * @return array
     */
    public function getGeoPoint(): array
    {
        return $this->geoPoint;
    }

    /**
     * @param array $geoPoint
     * @return EventPlace
     */
    public function setGeoPoint(array $geoPoint): EventPlace
    {
        $this->geoPoint = $geoPoint;

        return $this;
    }

    /**
     * @return Event
     */
    public function getEvents(): Event
    {
        return $this->events;
    }

    /**
     * @param Event $events
     * @return EventPlace
     */
    public function setEvents(Event $events): EventPlace
    {
        $this->events = $events;

        return $this;
    }

    public function addEvent(Event $event)
    {
        $this->events[] = $event;

        return $this;
    }
}
