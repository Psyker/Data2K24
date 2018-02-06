<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsersData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Create Users to import.
     *
     * @return array
     */
    private function getUsers()
    {
        return
            [
                $this->getParam('admin_username'),
            ];
    }

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getUsers() as $key => $user) {
            $user = (new User())
                ->setUsername($this->getUsers()[0])
                ->setApiKey(strtoupper(substr(sha1(uniqid(mt_rand(), true)), 0, 16)))
                ->setApiSecret(sha1(uniqid(mt_rand(), true)));
            $manager->persist($user);
        }
        $manager->flush();
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     * @return ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        return $this->container = $container;
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
