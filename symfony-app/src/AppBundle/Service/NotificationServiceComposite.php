<?php

namespace AppBundle\Service;

use AppBundle\Entity\Notification;
use AppBundle\Exception\NotificationTransportException;
use Psr\Log\LoggerInterface;

/**
 * Class NotificationServiceComposite
 * @package AppBundle\Service
 */
class NotificationServiceComposite implements NotificationServiceInterface
{
    /** @var LoggerInterface */
    private $logger;

    /** @var array  */
    private $services = [];

    /**
     * Instantiates the notification composite service.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Adds a notification service to the composite service.
     *
     * @param NotificationServiceInterface $service
     */
    public function add(NotificationServiceInterface $service)
    {
        $this->services[] = $service;
    }

    /**
     * Removes a notification service from the composite service.
     *
     * @param NotificationServiceInterface $service
     */
    public function remove(NotificationServiceInterface $service)
    {
        foreach ($this->services as $key => $tmpService) {
            if ($service === $tmpService) {
                unset($this->services[$key]);
            }
        }
    }

    /**
     * Dispatches a temperature alert.
     *
     * @param Notification $notification
     * @return mixed
     */
    public function temperatureAlert(Notification $notification)
    {
        /** @var NotificationServiceInterface $service */
        foreach ($this->services as $service) {
            try {
                $service->temperatureAlert($notification);
            } catch (NotificationTransportException $e) {
                $this->logger->error($e->getMessage());
                $this->logger->error($e->getTraceAsString());
            }
        }
    }

    /**
     * Dispatches a system alert.
     *
     * @param Notification $notification
     * @return mixed
     */
    public function systemAlert(Notification $notification)
    {
        /** @var NotificationServiceInterface $service */
        foreach ($this->services as $service) {
            try {
                $service->systemAlert($notification);
            } catch (NotificationTransportException $e) {
                $this->logger->error($e->getMessage());
                $this->logger->error($e->getTraceAsString());
            }
        }
    }
}
