<?php

namespace AppBundle\Command;

use AppBundle\Entity\District;
use AppBundle\Entity\LivingPlace;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppImportDataCommand extends ContainerAwareCommand
{
    CONST DATA_MAX = 1000;
    CONST BASE_URI_OP_PARIS = 'https://opendata.paris.fr/api/records/1.0/search/';
    CONST BASE_URI_OPS = 'https://public.opendatasoft.com/api/records/1.0/search/';

    /**
     * @var ContainerInterface
     */
    private $em;

    /**
     * @var Client
     */
    private $client;

    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->client = new Client();
    }

    protected function configure()
    {
        $this
            ->setName('app:import-data')
            ->setDescription('Import data from many public API')
        ;
    }

    private function createLivingPlaces()
    {
        $livingPlaceApiUri = self::BASE_URI_OP_PARIS.'?dataset=commercesparis';
        $response = $this->client->get($livingPlaceApiUri);
        $countData = intval($this->decode($response)['nhits']);
        $start = 0;
        for ($i = 1; $i <= round($countData/self::DATA_MAX); $i++) {
            $dataResponse = $this->decode($this->client->get($livingPlaceApiUri.'&rows='.self::DATA_MAX.'&start='.$start));
            foreach ($dataResponse['records'] as $itemData) {
                $itemDataFields = $itemData['fields'];
                $newLivingPlace = (new LivingPlace())
                    ->setActivityCode($itemDataFields['codact'])
                    ->setCoordinates($itemDataFields['xy'])
                    ->setArr($itemDataFields['arro'])
                    ->setAddress($itemDataFields['adresse_complete'])
                    ->setActivityLabel($itemDataFields['libact'])
                    ->setSituation($itemDataFields['type_voie'])
                    ->setArea($itemDataFields['surface']);
                $this->em->persist($newLivingPlace);
            }
            $this->em->flush();
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createLivingPlaces();
    }



    private function decode(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }

}
