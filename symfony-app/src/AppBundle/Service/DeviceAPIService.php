<?php

namespace AppBundle\Service;


use AppBundle\Entity\Device;
use AppBundle\Entity\Probe;
use AppBundle\Entity\TimeBoundArray;
use Buzz\Browser;
use Buzz\Message\Request;
use Buzz\Exception\ExceptionInterface as BuzzException;
use Psr\Log\LoggerInterface;

/**
 * Class DeviceAPIService
 * Service for managing
 *
 * @package AppBundle\Service
 */
class DeviceAPIService implements DeviceAPIServiceInterface
{
    /** @var Browser */
    private $api;

    /** @var LoggerInterface */
    private $logger;

    /** @var \DateTime */
    private $lastValue;

    /**
     * Instantiates the device api.
     *
     * @param Browser $api
     * @param LoggerInterface $logger
     */
    public function __construct(Browser $api, LoggerInterface $logger)
    {
        $this->api = $api;
        $this->logger = $logger;
    }

    /**
     * @param Device $device
     * @return boolean
     */
    public function checkConnectivity(Device $device)
    {
        try {
            $result = $this->apiCall($device);
            return isset($result['status']);
        } catch (BuzzException $e) { }

        return false;
    }

    /**
     * @param Device $device
     * @return boolean
     */
    public function checkAuthentication(Device $device)
    {
        try {
            $result = $this->apiCall($device);
            return isset($result['status']) && 404 === $result['status'];
        } catch (BuzzException $e) { }

        return false;
    }

    /**
     * @param Device $device
     * @return mixed
     */
    public function queryValues(Device $device)
    {
        $result = $this->apiCall($device,['action' => 'latest', 'limit' => 4]);

        if (!isset($result['values']) && !is_array($result['values'])) {
            return [];
        }

        $values = [];
        $tmpLastValue = null;
        foreach ($result['values'] as $time => $data) {
            $time = new \DateTime($time);

            /* Skip values we have seen already */
            if ($this->lastValue && $this->lastValue >= $time) {
                continue;
            }

            if (!$tmpLastValue || $tmpLastValue < $time) {
                $tmpLastValue = $time;
            }

            $tmpValues = new TimeBoundArray();
            $tmpValues->setTime($time);

            $probes = $device->getProbes();

            /** @var Probe $probe */
            foreach ($probes as $probe) {
                $id = $probe->getId();
                $c = $probe->getChannel();
                $tmpValues->set($id, isset($data[$c]) ? $data[$c] : null);
            }

            $values[] = $tmpValues;
        }

        if ($tmpLastValue) {
            $this->lastValue = $tmpLastValue;
        }

        return $values;
    }

    /**
     * Saves the probe configuration of the given device.
     *
     * @param Device $device
     * @return mixed
     */
    public function saveProbeConfig(Device $device)
    {
        /** @var Probe $probe */
        foreach ($device->getProbes() as $probe) {
            $this->apiCall($device, ['action' => 'config'], [
                'index' => $probe->getChannel(),
                'enabled' => true,
                'probeType' => $probe->getType(),
            ]);
        }
    }

    /**
     * Restarts the given device.
     *
     * @param Device $device
     * @return mixed
     */
    public function restart(Device $device)
    {
        $this->apiCall($device, ['action' => 'restart']);
    }

    /**
     * Shuts down the given device.
     *
     * @param Device $device
     * @return mixed
     */
    public function shutdown(Device $device)
    {
        $this->apiCall($device, ['action' => 'shutdown']);
    }

    /**
     * Makes an API call for the given device.
     *
     * @param Device $device
     * @param array $params
     * @param array $post
     * @return array
     */
    private function apiCall(Device $device, array $params = [], array $post = [])
    {
        $method = count($post) > 0 ? Request::METHOD_POST : Request::METHOD_GET;

        $action = '/';
        if (count($params) > 0) {
            $action .= '?' . http_build_query($params);
        }

        $request = new Request($method, $action, $device->getUrl());

        if (count($post) > 0) {
            $request->setContent(http_build_query($post));
        }

        if ($device->getUsername() && $device->getPassword()) {
            $hdr = 'Authorization: Basic ' . base64_encode($device->getUsername() . ':' . $device->getPassword());
            $request->addHeader($hdr);
        }

        $this->logger->debug(var_export($request, true));

        $response = $this->api->send($request);

        $this->logger->debug(var_export($response, true));

        return json_decode(
            $response->getContent(),
            true
        );
    }
} 