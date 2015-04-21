<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Subscription
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\GCMSubscriptionRepository")
 * @UniqueEntity({"registrationId"}, message="gcmsubscriptions.registrationId.uniqueEntity")
 */
class GCMSubscription
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="registrationId", type="string", length=255)
     * @Assert\NotBlank(message="gcmsubscriptions.registrationId.notBlank")
     */
    private $registrationId;

    /**
     * @var string
     *
     * @ORM\Column(name="payload", type="text", nullable=true)
     */
    private $payload;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRegistrationId()
    {
        return $this->registrationId;
    }

    /**
     * @param int $registrationId
     */
    public function setRegistrationId($registrationId)
    {
        $this->registrationId = $registrationId;
    }

    /**
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param string $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Returns the unserialized payload.
     *
     * @return array
     */
    public function getNotifications($reset = false)
    {
        $payload = unserialize($this->payload);

        if ($reset) {
            $this->payload = '';
        }

        return is_array($payload) ? $payload : [];
    }

    /**
     * Adds a notification to the payload.
     *
     * @param Notification $notification
     */
    public function addNotification(Notification $notification)
    {
        $payload = $this->getNotifications();
        $payload[] = $notification;
        $this->payload = serialize($payload);
    }
}
