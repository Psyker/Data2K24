<?php

namespace AppBundle\Command;

use AppBundle\Entity\District;
use AppBundle\Entity\LivingPlace;
use AppBundle\Entity\Station;
use AppBundle\Entity\StationTrafic;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
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

    private $stationSchema = [
        'ligne' => 'setLineHint',
        'geo_point_2d' => 'setCoordinates',
        'reseau' => 'setNetwork',
        'nomlong' => 'setName',
        'exploitant' => 'setOperator'
    ];

    private $districtSchema = [
        'geo_point_2d' => 'setGeoPoint',
        'typ_iris' => 'setTypIris',
        'p12_pop' => 'setP12Pop',
        'denspop12' => 'setDensPop12',
        'p12_h0014' => 'setPop12H0014',
        'p12_h1529' => 'setP12H1529',
        'p12_h3044' => 'setP12H3044',
        'p12_h4559' => 'setP12H4559',
        'p12_h6074' => 'setP12H6074',
        'p12_h75p' => 'setP12H75p',
        'p12_pop60p' => 'setP12Pop60',
        'p12_pop001' => 'setP12Pop001'
    ];

    private $stationTraficSchema = [
        'ville' => 'setCity',
        'rang' => 'setRank',
        'reseau' => 'setNetwork',
        'trafic' => 'setTrafic',
        'station' => 'setStation',
        'arrondissement_pour_paris' => 'setArr'
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

    private function createEntities(string $apiUri, string $entity, array $model, OutputInterface $output)
    {
        @ini_set('memory_limit', -1);
        $output->writeln([
            PHP_EOL,
            'Create ' . $entity . ' entities from JSON export.',
            '<comment>Downloading JSON file ...</comment>'
        ]);
        $response = $this->decode($this->client->get($apiUri));
        $output->write('<info>Downloaded.</info>', ['newline' => true]);
        $chunkSize = 500;
        $index = 0;
        $progressBar = new ProgressBar($output, count($response));
        $output->write('<comment>Starting to generate entities.</comment>', ['newline' => true]);
        $progressBar->start();
        foreach ($response as $itemData) {
            $newEntity = $this->setDataByModel($model, $itemData['fields'], new $entity());
            if ($entity === Station::class) {
                if ($newEntity->getOperator() == 'SNCF') {
                    $newEntity = null;
                } else {
                    /** @var StationTrafic $stationTrafic */
                    if (!empty($stationTrafic = $this->em->getRepository(StationTrafic::class)->findOneBy([
                        'stationName' => strtoupper($itemData['fields']['nomlong'])
                    ]))) {
                        /** @var $entity Station */
                        $newEntity->setStationTrafic($stationTrafic);
                    }
                }
            }
            if (!is_null($newEntity)) {
                $this->em->persist($newEntity);
            }
            $index++;
            $progressBar->advance();
            if (($index % $chunkSize) == 0) {
                $progressBar->setMessage(PHP_EOL.'Flushing 500 entities.');
                $this->em->flush();
                $this->em->clear();
                $progressBar->setMessage('Keep going.');
            }
        }
        $progressBar->finish();
        $output->write(PHP_EOL.'Flushing last entities ...', ['newline' => true]);
        $this->em->flush();
        $this->em->clear();
        $output->write('<info>Done with success.</info>', ['newline' => true]);
        unset($response);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em->getConnection()->query('SET foreign_key_checks = 0;')->execute();
        $this->em->getConnection()->query('TRUNCATE TABLE living_place')->execute();
        $this->em->getConnection()->query('TRUNCATE TABLE station_trafic')->execute();
        $this->em->getConnection()->query('TRUNCATE TABLE station')->execute();
        $this->em->getConnection()->query('TRUNCATE TABLE district')->execute();
        $this->em->getConnection()->query('SET foreign_key_checks = 1;')->execute();

//        dump($this->decode($this->client->get('https://opendata.stif.info/explore/dataset/emplacement-des-gares-idf/download/?format=json&timezone=Europe/Berlin'))[0]);exit;

        $this->createEntities(
            'https://dataratp2.opendatasoft.com/explore/dataset/trafic-annuel-entrant-par-station-du-reseau-ferre-2016/download/?format=json&timezone=Europe/Berlin',
            StationTrafic::class,
            $this->stationTraficSchema,
            $output
        );

        // Create Stations from json export.
        $this->createEntities(
            'https://opendata.stif.info/explore/dataset/emplacement-des-gares-idf/download/?format=json&timezone=Europe/Berlin',
            Station::class,
            $this->stationSchema,
            $output
        );

        ;exit;

        // Create Districts from json export.
        $this->createEntities(
            'https://public.opendatasoft.com/explore/dataset/iris-demographie/download/?format=json&timezone=Europe/Berlin',
            District::class,
            $this->districtSchema,
            $output
        );

        // Create Living Places from json export.
        $this->createEntities(
            'https://opendata.paris.fr/explore/dataset/commercesparis/download/?format=json&timezone=Europe/Berlin',
            LivingPlace::class,
            $this->livingPlaceSchema,
            $output
        );
    }

    private function decode(ResponseInterface $response)
    {
        return json_decode(utf8_encode($response->getBody()->getContents()), true);
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
