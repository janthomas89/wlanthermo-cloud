<?php

namespace AppBundle\Service;


use AppBundle\Entity\GCMSubscription;
use AppBundle\Entity\GCMSubscriptionRepository;
use AppBundle\Entity\Notification;
use AppBundle\Exception\NotificationTransportException;
use Doctrine\ORM\EntityManager;

/**
 * Class NotificationServiceMail
 * Service for sending notifications via Google Cloud Messaging.
 *
 * @package AppBundle\Service
 */
class NotificationServiceGCM implements NotificationServiceInterface
{
    /** @var EntityManager */
    private $em;

    /** @var GoogleCloudMessagingService */
    private $gcmService;

    /**
     * Instantiates the GCM Notification service.
     *
     * @param EntityManager $em
     * @param GoogleCloudMessagingService $gcmService
     */
    public function __construct(EntityManager $em, GoogleCloudMessagingService $gcmService)
    {
        $this->em = $em;
        $this->gcmService = $gcmService;
    }

    /**
     * Dispatches a temperature alert.
     *
     * @param Notification $notification
     * @return mixed
     * @throws NotificationTransportException
     */
    public function temperatureAlert(Notification $notification)
    {
        $this->dispatch($notification);
    }

    /**
     * Dispatches a system alert.
     *
     * @param Notification $notification
     * @return mixed
     * @throws NotificationTransportException
     */
    public function systemAlert(Notification $notification)
    {
        $this->dispatch($notification);
    }

    /**
     * Dispatches the given notification.
     *
     * @param Notification $notification
     * @throws NotificationTransportException
     */
    private function dispatch(Notification $notification)
    {
        /** @var GCMSubscriptionRepository $repo */
        $repo = $this->em->getRepository('AppBundle:GCMSubscription');

        /** @var GCMSubscription $subscription */
        foreach ($repo->findAll() as $subscription) {
            $subscription->addNotification($notification);
            $this->em->persist($subscription);
            $this->em->flush();

            $result = $this->gcmService->send($subscription);

            $this->handleGcmResult($result, $subscription);
        }
    }

    /**
     * Handles the result of the GCM request.
     *
     * @param array $result
     * @param GCMSubscription $subscription
     */
    private function handleGcmResult(array $result, GCMSubscription $subscription)
    {
        if (!isset($result['results'])) {
            return;
        }

        /* If we have a invalid subscription, unsubscribe! */
        foreach ($result['results'] as $tmpResult) {
            if (isset($tmpResult['error']) && 'InvalidRegistration' === $tmpResult['error']) {
                $this->em->remove($subscription);
                $this->em->flush();
            }
        }
    }
}
