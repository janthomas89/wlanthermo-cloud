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
}
