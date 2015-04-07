<?php

namespace AppBundle\Service;

use AppBundle\Entity\Notification;

/**
 * Interface NotificationServiceInterface
 * @package AppBundle\Service
 */
interface NotificationServiceInterface
{
    /**
     * Dispatches a temperature alert.
     *
     * @param Notification $notification
     * @return mixed
     */
    public function temperatureAlert(Notification $notification);

    /**
     * Dispatches a system alert.
     *
     * @param Notification $notification
     * @return mixed
     */
    public function systemAlert(Notification $notification);
}
