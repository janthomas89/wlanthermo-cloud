<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Probe
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ProbeRepository")
 * @UniqueEntity({"channel", "device"}, message="probes.channel.uniqueEntity")
 */
class Probe
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
     * @var integer
     *
     * @ORM\Column(name="channel", type="smallint")
     * @Assert\NotBlank(message="probes.channel.notBlank")
     */
    private $channel;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     * @Assert\NotBlank(message="probes.type.notBlank")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="defaultName", type="string", length=255)
     * @Assert\NotBlank(message="probes.deaultName.notBlank")
     */
    private $defaultName;

    /**
     * @var string
     *
     * @ORM\Column(name="defaultColor", type="string", length=9)
     * @Assert\NotBlank(message="probes.defaultColor.notBlank")
     * @Assert\Regex(pattern="/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/", message="probes.defaultColor.notBlank")
     */
    private $defaultColor;

    /**
     * @var Device
     *
     * @ORM\ManyToOne(targetEntity="Device", inversedBy="probes")
     * @ORM\JoinColumn(name="deviceId", referencedColumnName="id")
     */
    private $device;

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
     * Set channel
     *
     * @param integer $channel
     * @return Probe
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return integer 
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Probe
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set defaultName
     *
     * @param string $defaultName
     * @return Probe
     */
    public function setDefaultName($defaultName)
    {
        $this->defaultName = $defaultName;

        return $this;
    }

    /**
     * Get defaultName
     *
     * @return string 
     */
    public function getDefaultName()
    {
        return $this->defaultName;
    }

    /**
     * Set defaultColor
     *
     * @param string $defaultColor
     * @return Probe
     */
    public function setDefaultColor($defaultColor)
    {
        $this->defaultColor = $defaultColor;

        return $this;
    }

    /**
     * Get defaultColor
     *
     * @return string 
     */
    public function getDefaultColor()
    {
        return $this->defaultColor;
    }

    /**
     * Returns the associated device.
     *
     * @return Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Sets the associated device.
     *
     * @param Device $device
     */
    public function setDevice(Device $device)
    {
        $this->device = $device;
    }
}
