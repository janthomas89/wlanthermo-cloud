<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MeasurementCommand
 * Command for monitoring a device and populate the measurement data.
 * @package AppBundle\Command
 */
class MeasurementCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:measurement')
            ->setDescription('Wlanthermo measurement command')
            ->addArgument(
                'measurementId',
                InputArgument::REQUIRED,
                'Please provide a measurement id'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = (int)$input->getArgument('measurementId');

        for ($i = 0; true; $i++) {
            $output->writeln($id . ', iteration: ' . $i . ', memory usage:' . (memory_get_usage()/1024/1024));
            sleep(1);
        }
    }
} 