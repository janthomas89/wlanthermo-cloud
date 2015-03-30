<?php

namespace AppBundle\Command;

use AppBundle\Entity\Measurement;
use AppBundle\Service\MeasurementService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class MeasurementCommand
 * Command for monitoring a device and populate the measurement data.
 * @package AppBundle\Command
 */
class MeasurementCommand extends AbstractInfiniteCommand
{
    /** @var Measurement */
    protected $measurement;

    /** @var MeasurementService */
    protected $service;

    /**
     * Configures the command.
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('app:measurement')
            ->setDescription('Wlanthermo measurement command')
            ->addArgument(
                'measurementId',
                InputArgument::REQUIRED,
                'Please provide a measurement id'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = (int)$input->getArgument('measurementId');
        $this->measurement = $this->getMeasurement($id);

        if (!$this->measurement) {
            $output->writeln('Measurement not found');
            return;
        }

        if (!$this->measurement->isActive()) {
            $output->writeln('Only active measurements can be monitored');
            return;
        }

        $this->service = $this->getContainer()->get('measurement_service');

        parent::execute($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function tick(InputInterface $input, OutputInterface $output)
    {
        /* Refresh the measurement every 5 ticks. */
        if ($this->ticks % 5 == 4) {
            $output->writeln('refreshing measurement entity');
            $this->refreshMeasurement();
        }

        /* Check wether the measurement still is active */
        if (!$this->measurement->isActive()) {
            $output->writeln('Measurement stopped, command terminates');
            return $this->terminate();
        }

        /* Executes one measurement tick */
        $output->writeln('Execute measurement');
        $this->service->execute($this->measurement);

        // ToDo Error handling => Device not available? ...
    }

    /**
     * Retrieves a measurement for the given id.
     *
     * @param int $id
     * @return Measurement|null
     */
    protected function getMeasurement($id)
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('AppBundle:Measurement')->find($id);
    }

    /**
     * Refreshes the measurement such that inactivity can be detected.
     */
    protected function refreshMeasurement()
    {
        if ($this->measurement) {
            $em = $this->getDoctrine()->getManager();
            $em->refresh($this->measurement);
        }
    }
} 