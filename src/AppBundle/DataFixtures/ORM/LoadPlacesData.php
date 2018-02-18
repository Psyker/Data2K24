<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\EventPlace;
use AppBundle\Entity\TouristicPlace;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Event;

class LoadPlacesData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        return $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $eventPlaces = file_get_contents($this->container->get('kernel')->getRootDir().'/../src/AppBundle/DataFixtures/Documents/eventPlaces.json', 'r');
        $touristicPlaces = file_get_contents($this->container->get('kernel')->getRootDir().'/../src/AppBundle/DataFixtures/Documents/touristicPlace.json', 'r');

        $this->container->get('doctrine')->getConnection()->query('SET foreign_key_checks = 0;')->execute();
        $this->container->get('doctrine')->getConnection()->query('TRUNCATE TABLE event_place')->execute();
        $this->container->get('doctrine')->getConnection()->query('TRUNCATE TABLE event')->execute();
        $this->container->get('doctrine')->getConnection()->query('SET foreign_key_checks = 1;')->execute();

        $decodedTouristicPlaces = json_decode($touristicPlaces, true);
        foreach ($decodedTouristicPlaces as $touristicPlace) {
            $newTouristicPlace = new TouristicPlace();
            $newTouristicPlace->setGeoPoint2d($touristicPlace['geo_point_2d'])
                ->setAnnualFrequency($touristicPlace['annualFrequency'])
                ->setPlaceName($touristicPlace['placeName']);
            $manager->persist($newTouristicPlace);
        }
        $manager->flush();

        $decodedEventPlaces = \GuzzleHttp\json_decode($eventPlaces, true);
        /** @var array $eventPlace */
        foreach ($decodedEventPlaces as $eventPlace) {
            /** @var EventPlace $newEventPlace */
            $newEventPlace = new EventPlace();
            $newEventPlace->setName($eventPlace['placeName'])
                ->setGeoPoint($eventPlace['geo_point_2d'])
                ->setCapacity($eventPlace['capacity']);

            /** @var array $trial */
            foreach ($eventPlace['events'] as $trial) {
                /** @var int $timestamp */
                foreach($trial['timeStamp'] as $timestamp) {
                    $timestampStart = $timestamp;
                    $timestampEnd = ((new \DateTime())->setTimestamp($timestamp))->modify('+2 hour');
                    $newEvent = new Event();
                    $newEvent->setName($trial['name'])
                        ->setDates([$timestampStart, $timestampEnd->getTimestamp()])
                        ->setFiling($trial['filing'])
                        ->setStepName($trial['stepName'])
                        ->setStepFinal($trial['stepFinal']);
                    $newEventPlace->addEvent($newEvent);
                    $newEvent->setEventPlace($newEventPlace);
                    $manager->persist($newEvent);
                }

            }
            $manager->persist($newEventPlace);
        }
        $manager->flush();
    }
}