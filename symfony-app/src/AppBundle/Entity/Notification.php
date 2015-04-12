<?php

namespace AppBundle\Entity;

/**
 * Class Notification
 * @package AppBundle\Entity
 */
class Notification
{
    /** @var string */
    private $subject;

    /** @var string */
    private $msg;

    /** @var string */
    private $identifier;

    /** @var \DateInterval */
    private $throttleFor;

    /**
     * Instantiates the Notification
     *
     * @param string $subject
     * @param string $msg
     */
    public function __construct($subject = '', $msg = '')
    {
        $this->setSubject($subject);
        $this->setMsg($msg);
    }

    /**
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param string $msg
     * @return Notification
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return Notification
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return Notification
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @return \DateInterval
     */
    public function getThrottleFor()
    {
        return $this->throttleFor;
    }

    /**
     * @param \DateInterval $throttleFor
     * @return Notification
     */
    public function setThrottleFor(\DateInterval $throttleFor)
    {
        $this->throttleFor = $throttleFor;
        return $this;
    }
}
