<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\TouristicPlace;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

        $decodedTouristicPlaces = json_decode($touristicPlaces, true);
        foreach ($decodedTouristicPlaces as $touristicPlace) {
            $newTouristicPlace = new TouristicPlace();
            $newTouristicPlace->setGeoPoint2d($touristicPlace['geo_point_2d'])
                ->setAnnualFrequency($touristicPlace['annualFrequency'])
                ->setPlaceName($touristicPlace['placeName']);
            $manager->persist($newTouristicPlace);
        }
        $manager->flush();


    }

    /**
     * @param string $name
     * @return mixed
     */
    private function getParam(string $name)
    {
        return $this->container->getParameter($name);
    }
}