<?php

namespace AppBundle\Service;


use AppBundle\Entity\Notification;
use AppBundle\Exception\NotificationTransportException;

/**
 * Service for sending notifications via mail.
 * Class NotificationServiceMail
 * @package AppBundle\Service
 */
class NotificationServiceMail implements NotificationServiceInterface
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var string */
    private $sender;

    /** @var string */
    private $receiver;

    /**
     * Instantiates the service.
     *
     * @param \Swift_Mailer $mailer
     * @param string
     * @param string
     */
    public function __construct(\Swift_Mailer $mailer, $sender, $receiver)
    {
        $this->mailer = $mailer;
        $this->sender = $sender;
        $this->receiver = $receiver;
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
        $message = $this->mailer->createMessage()
            ->setSubject($notification->getSubject())
            ->setFrom($this->sender)
            ->setTo($this->receiver)
            ->setBody($notification->getMsg(), 'text/plain')
        ;

        $res = $this->mailer->send($message);

        if (!$res) {
            $msg = 'Notification could not be sent via swiftmailer';
            throw new NotificationTransportException($msg);
        }
    }
}
