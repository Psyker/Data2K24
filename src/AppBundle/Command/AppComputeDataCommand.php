<?php

namespace AppBundle\Command;

use AppBundle\Services\LivingPlaceService;
use AppBundle\Services\TouristicService;
use AppBundle\Services\TransportService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppComputeDataCommand extends ContainerAwareCommand
{

    private $transportService;

    private $touristicService;

    private $livingPlaceService;

    public function __construct(TransportService $transportService, TouristicService $touristicService, LivingPlaceService $livingPlaceService)
    {
        parent::__construct();
        $this->transportService = $transportService;
        $this->touristicService = $touristicService;
        $this->livingPlaceService = $livingPlaceService;
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
        $output->writeln(['Computing frequency for each touristic place', '<info>Calculating...</info>']);
        $this->touristicService->getFrequency();
        $output->writeln('Done.');

        $output->writeln(['Computing frequency for each stations', '<info>Calculating...</info>']);
        $this->transportService->getFrequency();
        $output->writeln('Done.');

        $output->writeln(['Computing frequency for each living place', '<info>Calculating...</info>']);
        $this->livingPlaceService->getFrequency();
        $output->writeln('Done.');
    }
}
