<?php

namespace AppBundle\Service;
use AppBundle\Entity\Notification;
use Doctrine\Common\Cache\Cache;

/**
 * Class NotificationServiceThrottle
 * Notification service that throttles all notifications.
 *
 * @package AppBundle\Service
 */
class NotificationServiceThrottle implements NotificationServiceInterface
{
    /** @var NotificationServiceInterface */
    private $service;

    /** @var Cache */
    private $cache;

    /**
     * @param NotificationServiceInterface $service
     * @param Cache $cache
     */
    public function __construct(NotificationServiceInterface $service, Cache $cache)
    {
        $this->service = $service;
        $this->cache = $cache;
    }

    /**
     * Dispatches a temperature alert.
     *
     * @param Notification $notification
     * @return mixed
     */
    public function temperatureAlert(Notification $notification)
    {
        if (!$this->shouldThrottle($notification)) {
            $this->saveLastDispatchTime($notification->getIdentifier());
            $this->service->temperatureAlert($notification);
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
        if (!$this->shouldThrottle($notification)) {
            $this->saveLastDispatchTime($notification->getIdentifier());
            $this->service->systemAlert($notification);
        }
    }

    /**
     * Decices wether to throttle the given notification or not.
     *
     * @param Notification $notification
     * @return bool
     */
    private function shouldThrottle(Notification $notification)
    {
        $throttleFor = $notification->getThrottleFor();

        if (!$throttleFor) {
            return false;
        }

        $id = $notification->getIdentifier();
        $time = $this->getLastDispatchTime($id);

        if (!$time) {
            return false;
        }

        $time->add($throttleFor);
        return $time > new \DateTime();
    }

    /**
     * Returns the time at which a notifcation has been sent
     * last for the given identifier.
     *
     * @param $identifier
     * @return \DateTime
     */
    private function getLastDispatchTime($identifier)
    {
        $key = $this->getCacheKey($identifier);
        $time = $this->cache->fetch($key);

        return $time;
    }

    /**
     * Saves the current time as last dispatch time for
     * the given identifier.
     *
     * @param $identifier
     */
    private function saveLastDispatchTime($identifier)
    {
        $key = $this->getCacheKey($identifier);
        $this->cache->save($key, new \DateTime());
    }

    /**
     * Calculates the cache key for the given identifier.
     *
     * @param $identifier
     * @return string
     */
    private function getCacheKey($identifier)
    {
        return hash('sha256', __CLASS__ . '_' . $identifier);
    }
}