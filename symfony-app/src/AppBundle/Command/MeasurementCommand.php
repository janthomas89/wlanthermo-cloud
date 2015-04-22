<?php

namespace AppBundle\Command;

use AppBundle\Entity\Measurement;
use AppBundle\Entity\MeasurementProbe;
use AppBundle\Entity\Notification;
use AppBundle\Service\MeasurementService;
use AppBundle\Service\NotificationServiceInterface;
use Buzz\Exception\RequestException as DeviceAPIException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MeasurementCommand
 * Command for monitoring a device and populate the measurement data.
 * @package AppBundle\Command
 */
class MeasurementCommand extends AbstractInfiniteCommand
{
    /** @var Measurement */
    private $measurement;

    /** @var MeasurementService */
    private $service;

    /** @var \DateTime */
    private $lastDeviceException;

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
        } catch (DeviceAPIException $e) {
            $output->writeln('Exception catched: ' . $e->getMessage());
            if (!$this->handleDeviceAPIException($e)) {
                $output->writeln('Exception alert skipped');
            }
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
    private function getMeasurement($id)
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('AppBundle:Measurement')->find($id);
    }

    /**
     * Refreshes the measurement such that inactivity can be detected.
     */
    private function refreshMeasurement()
    {
        if ($this->measurement) {
            $em = $this->getDoctrine()->getManager();
            $em->refresh($this->measurement);

            /** @var MeasurementProbe $probe */
            foreach ($this->measurement->getProbes() as $probe) {
                $em->refresh($probe);
            }
        }
    }

    /**
     * Handles a exception.
     *
     * @param \Exception $e
     */
    private function handleException(\Exception $e)
    {
        $notification = new Notification();
        $notification->setSubject('Error in measurement command: ' . $e->getMessage());
        $notification->setMsg($e->getMessage() . "\n\n" . $e->getTraceAsString());
        $notification->setIdentifier($e->getMessage());
        $notification->setThrottleFor(new \DateInterval('PT60S'));

        /** @var NotificationServiceInterface $service */
        $service = $this->getContainer()->get('notification_service');
        $service->systemAlert($notification);
    }

    /**
     * Handles a DeviceAPIException. Alerts are only created, if
     * the last exception arose within the last 10 seconds.
     *
     * @param DeviceAPIException $e
     * @return bool
     */
    private function handleDeviceAPIException(DeviceAPIException $e)
    {
        $handled = false;

        $threshold = new \DateTime();
        $threshold->sub(new \DateInterval('PT30S'));

        if ($this->lastDeviceException && $threshold < $this->lastDeviceException) {
            $this->handleException($e);
            $handled = true;
        }

        $this->lastDeviceException = new \DateTime();

        return $handled;
    }
}