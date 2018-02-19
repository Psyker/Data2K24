<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 * @ORM\Table(indexes={@Index(name="event_search_idx", columns={"filing", "step_name", "step_final"})})
 */
class Event
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
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var array
     * @ORM\Column(name="dates", type="simple_array", nullable=false)
     */
    private $dates;

    /**
     * @var int
     * @ORM\Column(type="float", name="filing", nullable=false)
     */
    private $filing;

    /**
     * @var EventPlace
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EventPlace", inversedBy="events")
     */
    private $eventPlace;

    /**
     * @ORM\Column(type="string", name="step_name", nullable=false)
     * @var string
     */
    private $stepName;

    /**
     * @ORM\Column(type="boolean", name="step_final", nullable=false)
     * @var bool
     */
    private $stepFinal;

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
     * @return Event
     */
    public function setName(string $name): Event
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getDates(): array
    {
        return $this->dates;
    }

    /**
     * @param array
     * @return Event
     */
    public function setDates(array $date): Event
    {
        $this->dates = $date;

        return $this;
    }

    /**
     * @return EventPlace
     */
    public function getEventPlace(): EventPlace
    {
        return $this->eventPlace;
    }

    /**
     * @param EventPlace $eventPlace
     * @return Event
     */
    public function setEventPlace(EventPlace $eventPlace): Event
    {
        $this->eventPlace = $eventPlace;

        return $this;
    }

    /**
     * @return float
     */
    public function getFiling(): float
    {
        return $this->filing;
    }

    /**
     * @param float $filing
     * @return Event
     */
    public function setFiling(float $filing): Event
    {
        $this->filing = $filing;

        return $this;
    }

    /**
     * @return string
     */
    public function getStepName(): string
    {
        return $this->stepName;
    }

    /**
     * @param string $stepName
     * @return Event
     */
    public function setStepName(string $stepName): Event
    {
        $this->stepName = $stepName;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStepFinal(): bool
    {
        return $this->stepFinal;
    }

    /**
     * @param bool $stepFinal
     * @return Event
     */
    public function setStepFinal(bool $stepFinal): Event
    {
        $this->stepFinal = $stepFinal;

        return $this;
    }
}
