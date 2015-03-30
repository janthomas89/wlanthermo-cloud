<?php

namespace AppBundle\Service;

use AppBundle\Entity\Device;
use AppBundle\Entity\Probe;
use AppBundle\Entity\TimeBoundArray;

/**
 * Class DeviceServiceMock
 * Mock Service for retrieving "random" probe values for a given device.
 * @package AppBundle\Service
 */
class DeviceServiceMock implements DeviceServiceInterface
{
    protected $lastValues = array();

    /**
     * Returns "random" probe values for the given device.
     *
     * @param Device $device
     * @return array|mixed
     */
    public function queryValues(Device $device)
    {
        $values = new TimeBoundArray();

        $probes = $device->getProbes();

        /** @var Probe $probe */
        foreach ($probes as $probe) {
            $id = $probe->getId();

            if (!isset($this->lastValues[$id])) {
                $this->lastValues[$id] = 0;
            }

            $delta = rand(0, 10) / 10;
            $values->set($id, $this->lastValues[$id] + $delta);
            $this->lastValues[$id] = $values->get($id);
        }

        return [$values];
    }
}