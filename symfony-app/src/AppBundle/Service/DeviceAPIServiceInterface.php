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
     * @return boolean
     */
    public function checkConnectivity(Device $device);

    /**
     * @param Device $device
     * @return boolean
     */
    public function checkAuthentication(Device $device);

    /**
     * @param Device $device
     * @return mixed
     */
    public function queryValues(Device $device);

    /**
     * Saves the probe configuration of the given device.
     *
     * @param Device $device
     * @return mixed
     */
    public function saveProbeConfig(Device $device);

    /**
     * Restarts the given device.
     *
     * @param Device $device
     * @return mixed
     */
    public function restart(Device $device);

    /**
     * Shuts down the given device.
     *
     * @param Device $device
     * @return mixed
     */
    public function shutdown(Device $device);
}
