<?php

namespace AppBundle\Service;
use AppBundle\Entity\Measurement;
use AppBundle\Entity\MeasurementRepository;
use AppBundle\Entity\MeasurementTimeSeriesRepository;
use AppBundle\Entity\Notification;
use Doctrine\ORM\EntityManager;

/**
 * Class HealthCheckService
 * Service for checking the health of the system.
 *
 * @package AppBundle\Service
 */
class HealthCheckService
{
    /** @var NotificationServiceInterface */
    private $notificationService;

    /** @var EntityManager */
    private $em;

    /** @var MeasurementDaemonService */
    private $daemonService;

    /** @var DeviceAPIServiceInterface */
    private $deviceAPIService;

    public function __construct(
        NotificationServiceInterface $notificationService,
        EntityManager $em,
        MeasurementDaemonService $daemonService,
        DeviceAPIService $deviceAPIService
    ) {
        $this->notificationService = $notificationService;
        $this->em = $em;
        $this->daemonService = $daemonService;
        $this->deviceAPIService = $deviceAPIService;
    }

    /**
     * Execute the health check.
     *
     * @return array
     */
    public function execute()
    {
        return [
            'daemon-availability' => $this->checkDaemonAvailability(),
            'daemon-measurement-pids' => $this->checkDaemonMeasurementPIds(),
            'device-availability' => $this->checkDeviceAvailability(),
            'recent-measurement-values' => $this->checkRecentMeasurementValues(),
        ];
    }

    /**
     * Checks wether the daemon is available or not.
     *
     * @return bool
     */
    private function checkDaemonAvailability()
    {
        return $this->check(
            $this->daemonService->isAvailable(),
            'Daemon is not available',
            'Daemon at localhost:1025 is not available. You may want to start the daemon: /etc/init.d/wlanthemro-daemon start'
        );
    }

    /**
     * Checks if all active measurements have a running measurement process.
     *
     * @return bool
     */
    private function checkDaemonMeasurementPIds()
    {
        $result = true;

        /** @var MeasurementRepository $repo */
        $repo = $this->em->getRepository('AppBundle:Measurement');

        /** @var Measurement $measurement */
        foreach ($repo->getActive() as $measurement) {
            $pid = $this->daemonService->getPIdForMeasurement($measurement);
            $tmpResult = $pid > 0;

            $this->check(
                $tmpResult,
                'No measurement process found for measurement ' . $measurement->getId(),
                'No measurement process found for measurement ' . $measurement->getId() . '. You may want to restart the measurement process.'
            );

            $result = $result && $tmpResult;
        }

        return $result;
    }

    /**
     * Checks if all actively used devices are online.
     *
     * @return bool
     */
    private function checkDeviceAvailability()
    {
        $result = true;

        /** @var MeasurementRepository $repo */
        $repo = $this->em->getRepository('AppBundle:Measurement');

        /** @var Measurement $measurement */
        foreach ($repo->getActive() as $measurement) {
            $device = $measurement->getDevice();
            $tmpResult = $this->deviceAPIService->checkConnectivity($device)
                && $this->deviceAPIService->checkAuthentication($device);

            $this->check(
                $tmpResult,
                'Device at ' . $device->getUrl() . ' is not available',
                'Device at ' . $device->getUrl() . ' is not available. You may want to check your network configuration and the device status.'
            );

            $result = $result && $tmpResult;
        }

        return $result;
    }

    private function checkRecentMeasurementValues()
    {
        $result = true;

        $threshold = new \DateTime();
        $threshold->sub(new \DateInterval('PT60S'));

        /** @var MeasurementRepository $repo */
        $repo = $this->em->getRepository('AppBundle:Measurement');

        /** @var MeasurementTimeSeriesRepository $repo */
        $repo2 = $this->em->getRepository('AppBundle:MeasurementTimeSeries');

        /** @var Measurement $measurement */
        foreach ($repo->getActive() as $measurement) {
            $time = $repo2->getLastMeasurement($measurement);
            $tmpResult = !$time || $time > $threshold;

            $this->check(
                $tmpResult,
                'No recent measurement values for measurement ' . $measurement->getId(),
                'No measurement values received within the last 60 seconds for measurement ' . $measurement->getId() . '. Last value received: '. ($time ? $time->format('d.m.Y H:i:s') : '')
            );

            $result = $result && $tmpResult;
        }

        return $result;
    }

    /**
     * Dispatches a alert through the notification service.
     *
     * @param bool $result
     * @param string $subject
     * @param string $message
     * @return bool
     */
    private function check($result, $subject, $message)
    {
        if (!$result) {
            $notification = new Notification();
            $notification->setSubject($subject);
            $notification->setMsg($message);
            $notification->setIdentifier($subject);
            $notification->setThrottleFor(new \DateInterval('PT180S'));

            $this->notificationService->systemAlert($notification);
        }

        return $result;
    }
} 