<?php

namespace AppBundle\Command;

use AppBundle\Entity\ComputedData;
use AppBundle\Entity\EventPlace;
use AppBundle\Entity\LivingPlace;
use AppBundle\Entity\Station;
use AppBundle\Entity\TouristicPlace;
use AppBundle\Repository\LivingPlaceRepository;
use AppBundle\Repository\StationRepository;
use AppBundle\Repository\TouristicPlaceRepository;
use AppBundle\Services\LivingPlaceService;
use AppBundle\Services\PlaceService;
use AppBundle\Services\TimeService;
use AppBundle\Services\TouristicService;
use AppBundle\Services\TransportService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PDO;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppComputeDataCommand extends ContainerAwareCommand
{

    private $transportService;

    private $touristicService;

    private $livingPlaceService;

    private $entityManager;

    private $timeService;

    private $placeService;

    public function __construct
    (
        EntityManager $entityManager,
        TransportService $transportService,
        TouristicService $touristicService,
        LivingPlaceService $livingPlaceService,
        TimeService $timeService,
        PlaceService $placeService
    ) {
        parent::__construct();
        $this->transportService = $transportService;
        $this->touristicService = $touristicService;
        $this->livingPlaceService = $livingPlaceService;
        $this->entityManager = $entityManager;
        $this->timeService = $timeService;
        $this->placeService = $placeService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:compute-data')
            ->setDescription('Compute the data.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        @ini_set('memory_limit', -1);
        $output->writeln(['Computing frequency for each touristic place', '<info>Calculating...</info>']);
        $this->touristicService->getFrequency();
        $output->writeln('Done.');

        $output->writeln(['Computing frequency for each stations', '<info>Calculating...</info>']);
        $this->transportService->getFrequency();
        $output->writeln('Done.');

        $output->writeln(['Computing frequency for each living place', '<info>Calculating...</info>']);
        $this->livingPlaceService->getFrequency();
        $output->writeln('Done.');

        $output->writeln(['Computing frequency for each event place', '<info>Calculating...</info>']);
        $this->placeService->getFrequency();
        $output->writeln('Done.');

        $this->entityManager->getConnection()->query('TRUNCATE TABLE event_place_stations')->execute();

        $output->writeln(['Getting closests stations for each event place', '<info>Calculating...</info>']);
        $this->placeService->getClosestStationsByEventPlace();
        $output->writeln('Done.');

        $output->writeln(['Computing hints for every places', '<info>Calculating...</info>']);
        $this->updateAllHints();
        $output->writeln('Done.');
    }

    private function updateAllHints()
    {
        $livingPlaceRepo = $this->entityManager->getRepository(LivingPlace::class);
        $stationRepo = $this->entityManager->getRepository(Station::class);
        $touristicPlaceRepo = $this->entityManager->getRepository(TouristicPlace::class);
        $eventRepo = $this->entityManager->getRepository(EventPlace::class);

        $this->updateHints($livingPlaceRepo);
        $this->updateHints($touristicPlaceRepo);
        $this->updateHints($stationRepo);
        $this->updateHints($eventRepo);
    }

    private function updateHints(EntityRepository $repo)
    {
        $places = $repo->getFrequenciesAndId();
        $index = 0;
        $chunkSize = 500;
        foreach ($places as $pInfos) {
            $slots = [];
            foreach ($pInfos['frequency'] as $frequency) {
                !($frequency > 10000) ?: $frequency = 10000;
                $hint = (float) number_format(($frequency * 10) / 10000,2);
                if ($hint > 0 && $hint < 0.5) {
                    $hint = 0.5;
                } else if ($hint > 10) {
                    $hint = 10;
                } elseif ($hint == 0) {
                    $hint = 0.1;
                }
                $slots[] = $hint;
            }
            $repo->find($pInfos['id'])->setHints($slots);
            $index++;
            if (($index % $chunkSize) == 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }
        $this->entityManager->flush();
    }
}
