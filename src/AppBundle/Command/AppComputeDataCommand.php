<?php

namespace AppBundle\Command;

use AppBundle\Services\TouristicService;
use AppBundle\Services\TransportService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppComputeDataCommand extends ContainerAwareCommand
{

    private $transportService;

    private $touristicService;

    public function __construct(TransportService $transportService, TouristicService $touristicService)
    {
        parent::__construct();
        $this->transportService = $transportService;
        $this->touristicService = $touristicService;
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
        $this->touristicService->getFrequency();


        $output->writeln(['Computing frequency for each stations', '<info>Calculating...</info>']);
        $this->transportService->getFrequency();
        $output->writeln('Done.');
    }
}
