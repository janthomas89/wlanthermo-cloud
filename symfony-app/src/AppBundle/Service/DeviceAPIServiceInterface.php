<?php

namespace AppBundle\Service;
use AppBundle\Entity\Device;

/**
 * Interface DeviceServiceInterface
 * @package AppBundle\Service
 */
interface DeviceAPIServiceInterface
{
    /**
     * @param Device $device
     * @return mixed
     */
    public function queryValues(Device $device);
}
