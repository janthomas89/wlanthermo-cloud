<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Constraint\DeviceInUseConstraint;

/**
 * Measurement
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MeasurementRepository")
 * @DeviceInUseConstraint(message="measurements.device.inUse")
 */
class Measurement
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message="measurements.name.notBlank")
     */
    private $name;

    /**
     * @var Device
     *
     * @ORM\ManyToOne(targetEntity="Device")
     * @ORM\JoinColumn(name="deviceId", referencedColumnName="id")
     * @Assert\NotBlank(message="measurements.device.notBlank")
     */
    private $device;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=true)
     */
    private $end;

    /**
     * @var integer
     *
     * @ORM\Column(name="pid", type="smallint", nullable=true)
     */
    private $pid;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MeasurementProbe", mappedBy="measurement", cascade={"persist"})
     * @Assert\Count(
     *      min = "1",
     *      max = "8",
     *      minMessage = "measurements.probes.min",
     *      maxMessage = "measurements.probes.max"
     * )
     */
    private $probes;

    /**
     * Instantiates the Measurement.
     */
    public function __construct()
    {
        $this->probes = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Measurement
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set device
     *
     * @param Device $device
     * @return Measurement
     */
    public function setDevice(Device $device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return Measurement
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Measurement
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Calculates the measurements duration in minutes.
     *
     * @return int
     */
    public function getDurationInMinutes()
    {
        $end = $this->isActive() ? new \DateTime() : $this->getEnd();
        $start = $this->getStart();

        return (int)ceil(($end->getTimestamp() - $start->getTimestamp()) / 60);
    }

    /**
     * Set pid
     *
     * @param integer $pid
     * @return Measurement
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Get pid
     *
     * @return integer 
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Returns the associated probes.
     *
     * @return ArrayCollection
     */
    public function getProbes()
    {
        return $this->probes;
    }

    /**
     * Adds a probe.
     *
     * @param MeasurementProbe $probe
     */
    public function addProbe(MeasurementProbe $probe)
    {
        $probe->setMeasurement($this);
        $this->probes->add($probe);
    }

    /**
     * Removes a probe.
     *
     * @param MeasurementProbe $probe
     */
    public function removeProbe(MeasurementProbe $probe)
    {
        $this->probes->removeElement($probe);
    }

    /**
     * Checks wether the measurement is active or not.
     *
     * @return boolean
     */
    public function isActive()
    {
        $now = new \DateTime();

        $start = $this->getStart();
        if (!$start || $start > $now) {
            return false;
        }

        $end = $this->getEnd();
        if ($end && $end < $now) {
            return false;
        }

        return true;
    }
}
