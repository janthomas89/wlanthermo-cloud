<?php

namespace AppBundle\Service;

use AppBundle\Entity\Notification;

/**
 * Class NotificationServiceComposite
 * @package AppBundle\Service
 */
class NotificationServiceComposite implements NotificationServiceInterface
{
    /** @var array  */
    private $services = [];

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
            $service->temperatureAlert($notification);
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
            $service->systemAlert($notification);
        }
    }
}
