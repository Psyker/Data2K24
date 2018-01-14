<?php

namespace AppBundle\Command;

use AppBundle\Entity\District;
use AppBundle\Entity\LivingPlace;
use AppBundle\Entity\Station;
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

    private $livingPlaceSchema = [
            'codact' => "setActivityCode",
            'xy' => 'setCoordinates',
            'arro' => 'setArr',
            'adresse_complete' => 'setAddress',
            'libact' => 'setActivityLabel',
            'type_voie' => 'setSituation',
            'surface' => 'setArea'
    ];

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



    private function createEntities(string $apiUri, $entity, array $model)
    {
        @ini_set('memory_limit', -1);
        $response = $this->decode($this->client->get($apiUri));
        $chunkSize = 500;
        $index = 0;
        foreach ($response as $itemData) {
            $newEntity = $this->setDataByModel($model, $itemData['fields'], new $entity());
            $this->em->persist($newEntity);
            $index++;
            if (($index % $chunkSize) == 0) {
                $this->em->flush();
                $this->em->clear();
            }
        }
        $this->em->flush();
        $this->em->clear();
    }


    private function createStations()
    {
        @ini_set('memory_limit', -1);
        $stationApiUri = 'http://data.ratp.fr/explore/dataset/positions-geographiques-des-stations-du-reseau-ratp/download/?format=json&timezone=Europe/Berlin';
        $response = $this->decode($this->client->get($stationApiUri));
            foreach ($response as $itemData) {
                $itemDataFields = $itemData['fields'];
                $newStation = (new Station())
                    ->setRecordId($itemData['recordid'])
                    ->setName($itemDataFields['stop_name'])
                    ->setDepartement($itemDataFields['departement'])
                    ->setCoordinates($itemDataFields['coord'])
                    ->setInseeCode($itemDataFields['code_postal'])
                    ->setDescription($itemDataFields['stop_desc']);
                $this->em->persist($newStation);
            }
            $this->em->flush();
            $this->em->clear();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em->getConnection()->query('TRUNCATE TABLE living_place')->execute();
        $this->em->getConnection()->query('TRUNCATE TABLE station')->execute();
        $this->createEntities(
            'https://opendata.paris.fr/explore/dataset/commercesparis/download/?format=json&timezone=Europe/Berlin',
            'AppBundle\\Entity\\LivingPlace',
            $this->livingPlaceSchema
        );

    }

    private function decode(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param array $model
     * @param array $data
     * @param $entity
     * @return mixed
     */
    private function setDataByModel(array $model, array $data, $entity)
    {
        foreach ($model as $key => $function) {
            if (array_key_exists($key, $data)) {
                $entity->set($function, $data[$key]);
            }
        }

        return $entity;
    }

}
