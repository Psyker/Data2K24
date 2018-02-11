<?php

namespace AppBundle\Command;

use AppBundle\Services\TransportService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppComputeDataCommand extends ContainerAwareCommand
{

    private $transportService;

    public function __construct(TransportService $transportService)
    {
        parent::__construct();
        $this->transportService = $transportService;
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
        $output->writeln(['Computing frequency for each stations', '<info>Calculating...</info>']);
        $this->transportService->getFrequency();
        $output->writeln('Done.');
    }
}
