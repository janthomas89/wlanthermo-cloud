<?php

namespace AppBundle\Service;


use AppBundle\Entity\Device;
use AppBundle\Entity\Probe;
use AppBundle\Entity\TimeBoundArray;
use Buzz\Browser;
use Buzz\Message\Request;

class DeviceAPIService implements DeviceAPIServiceInterface
{
    /** @var Browser */
    protected $api;

    /** @var \DateTime */
    protected $lastValue;

    /**
     * Instantiates the device api.
     *
     * @param Browser $api
     */
    public function __construct(Browser $api)
    {
        $this->api = $api;
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
     * Makes an API call for the given device.
     *
     * @param Device $device
     * @param array $params
     * @param array $post
     * @return array
     */
    protected function apiCall(Device $device, array $params = [], array $post = [])
    {
        $method = count($post) > 0 ? Request::METHOD_POST : Request::METHOD_GET;

        $action = '/';
        if (count($params) > 0) {
            $action .= '?' . http_build_query($params);
        }

        $request = new Request($method, $action, $device->getUrl());

        if (count($post) > 0) {
            $request->setContent(http_build_query($params));
        }

        if ($device->getUsername() && $device->getPassword()) {
            $hdr = 'Authorization: Basic ' . base64_encode($device->getUsername() . ':' . $device->getPassword());
            $request->addHeader($hdr);
        }

        $response = $this->api->send($request);

        return json_decode(
            $response->getContent(),
            true
        );
    }
} 