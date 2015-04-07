<?php

namespace AppBundle\Command;

use AppBundle\Entity\Measurement;
use AppBundle\Entity\Notification;
use AppBundle\Service\MeasurementService;
use AppBundle\Service\NotificationServiceInterface;
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

    /** @var \DateTime */
    protected $lastExceptionTime;

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
        try {
            $this->service->execute($this->measurement);
        } catch(\Exception $e) {
            $output->writeln('Exception catched: ' . $e->getMessage());
            $this->handleException($e);
        }
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

    /**
     * Handles a exception.
     *
     * @param \Exception $e
     */
    protected function handleException(\Exception $e)
    {
        $threshold = new \DateTime();
        $threshold->modify('-1 minutes');
        if ($this->lastExceptionTime && $threshold <= $this->lastExceptionTime) {
            return;
        }

        $notification = new Notification(
            'Error in measurement command: ' . $e->getMessage(),
            $e->getMessage() . "\n\n" . $e->getTraceAsString()
        );

        /** @var NotificationServiceInterface $service */
        $service = $this->getContainer()->get('notification_service');
        $service->systemAlert($notification);

        $this->lastExceptionTime = new \DateTime();
    }
} 