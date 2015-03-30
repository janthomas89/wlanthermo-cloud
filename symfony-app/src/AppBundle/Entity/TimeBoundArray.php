<?php

namespace AppBundle\Entity;

/**
 * Class ProbeValuesSnapshot
 * Utility class for time bound values (e.g. probe values).
 * @package AppBundle\Entity
 */
class TimeBoundArray
{
    /** @var \DateTime */
    protected $time;

    /** @var array */
    protected $values = [];

    /**
     * Instantiates the snapshot for the current time.
     */
    public function __construct()
    {
        $this->time = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values)
    {
        $this->values = $values;
    }

    /**
     * Sets one value for the given index.
     *
     * @param string $index
     * @param mixed $value
     */
    public function set($index, $value)
    {
        $this->values[$index] = $value;
    }

    /**
     * Returns one value for the given index.
     *
     * @param $index
     * @param null $default
     * @return mixed
     */
    public function get($index, $default = null)
    {
        return isset($this->values[$index]) ? $this->values[$index] : $default;
    }
} 