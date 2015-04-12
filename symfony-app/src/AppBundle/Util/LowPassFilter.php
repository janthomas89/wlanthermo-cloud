<?php

namespace AppBundle\Util;

/**
 * Class LowPassFilter
 * Implements a simple low pass filter.
 *
 * @package AppBundle\Util
 */
class LowPassFilter
{
    /** @var float The time constant rc */
    private $rc;

    /** @var float */
    private $lastValue;

    /** @var \DateTime */
    private $lastTime;

    /**
     * @param float $rc The time constant rc
     */
    public function __construct($rc)
    {
        $this->rc = $rc;
    }

    /**
     * Returns the time constant RC.
     *
     * @return float
     */
    public function getRc()
    {
        return $this->rc;
    }

    /**
     * Sets the time constant RC.
     *
     * @param float $rc
     * @return LowPassFilter
     */
    public function setRc($rc)
    {
        $this->rc = $rc;
        return $this;
    }

    /**
     * Resets the last value and the last time.
     */
    public function reset()
    {
        $this->lastTime = null;
        $this->lastValue = null;
    }

    /**
     * Applies the low pass onto the given value and rounds
     * the result with precision two.
     *
     * @param float $value
     * @param \DateTime $time
     * @return float
     */
    public function apply($value, \DateTime $time)
    {
        $filtered = $value;

        if ($this->lastTime) {
            $dt = $time->getTimestamp() - $this->lastTime->getTimestamp();
            $alpha = $dt / ($this->rc + $dt);

            $filtered = round($this->lastValue + $alpha * ($value - $this->lastValue), 2);
        }

        $this->lastTime = $time;
        $this->lastValue = $filtered;

        return $filtered;
    }
}
