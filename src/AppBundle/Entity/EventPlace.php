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
     * @var array
     * @ORM\Column(name="geo_point", type="simple_array", nullable=true)
     */
    private $geoPoint;

    /**
     * @var Event
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Event", mappedBy="eventPlace", cascade={"persist"})
     */
    private $events;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, name="capacity")
     */
    private $capacity;

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
    public function getEvents()
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

    /**
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->capacity;
    }

    /**
     * @param int $capacity
     * @return EventPlace
     */
    public function setCapacity(int $capacity): EventPlace
    {
        $this->capacity = $capacity;

        return $this;
    }
}
