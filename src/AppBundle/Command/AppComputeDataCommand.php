<?php

namespace AppBundle\Command;

use AppBundle\Entity\ComputedData;
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

        list($frequencies['living_place'], $frequencies['station'], $frequencies['touristic']) = [
            $livingPlaceRepo->getFrequency(),
            $this->entityManager->getRepository(Station::class)->getFrequency(),
            $this->entityManager->getRepository(TouristicPlace::class)->getFrequency()
        ];
        $allFrequencies = [];
        foreach ($frequencies as $frequencyType) {
            foreach ($frequencyType as $frequency) {
                foreach ($frequency['frequency'] as $value) {
                    $allFrequencies[] = (float) $value;
                }
            }
        }

        $maxValue = array_sum($allFrequencies) / count($allFrequencies);
        $this->updateHintsLivingPlace($livingPlaceRepo, $maxValue);
        $this->updateHintsTouristicPlace($touristicPlaceRepo, $maxValue);
        $this->updateHintsStations($stationRepo, $maxValue);
    }

    private function updateHintsLivingPlace(LivingPlaceRepository $livingPlaceRepo, $maxValue)
    {

        $livingPlaces = $livingPlaceRepo->getFrequenciesAndId();
        $index = 0;
        $chunkSize = 500;
        foreach ($livingPlaces as $lpInfos) {
            $slots = [];
            foreach ($lpInfos['frequency'] as $frequency) {
                $slots[] = number_format(($frequency * 10) / $maxValue,2);
            }
            $livingPlaceRepo->find($lpInfos['id'])->setHints($slots);
            $index++;
            if (($index % $chunkSize) == 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }
        $this->entityManager->flush();

    }

    private function updateHintsTouristicPlace(TouristicPlaceRepository $touristicPlaceRepo, $maxValue)
    {
        $touristicPlaces = $touristicPlaceRepo->getFrequenciesAndId();
        $index = 0;
        $chunkSize = 500;
        foreach ($touristicPlaces as $tpInfos) {
            $slots = [];
            foreach ($tpInfos['frequency'] as $frequency) {
                $slots[] = number_format(($frequency * 10) / $maxValue,2);
            }
            $touristicPlaceRepo->find($tpInfos['id'])->setHints($slots);
            $index++;
            if (($index % $chunkSize) == 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }
        $this->entityManager->flush();
    }

    private function updateHintsStations(StationRepository $stationRepo, $maxValue)
    {
        $stations = $stationRepo->getFrequenciesAndId();
        $index = 0;
        $chunkSize = 500;
        foreach ($stations as $sInfos) {
            $slots = [];
            foreach ($sInfos['frequency'] as $frequency) {
                $slots[] = number_format(($frequency * 10) / $maxValue,2);
            }
            $stationRepo->find($sInfos['id'])->setHints($slots);
            $index++;
            if (($index % $chunkSize) == 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }
        $this->entityManager->flush();
    }
}
