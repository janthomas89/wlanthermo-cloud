<?php

namespace AppBundle\Service;

use AppBundle\Entity\Measurement;
use Buzz\Browser;
use Buzz\Exception\RequestException;
use Buzz\Message\Request;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

/**
 * Class MeasurementDaemonService
 * Service class for interacting with the measurement daemon.
 *
 * @package AppBundle\Service
 */
class MeasurementDaemonService
{
    /** URL for the measurement daemon */
    const DAEMON_URL = 'http://localhost:1025';

    /** @var EntityManager */
    private $em;

    /** @var Browser */
    private $browser;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Instantiates the device api.
     *
     * @param Browser $browser
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManager $em, Browser $browser, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->browser = $browser;
        $this->logger = $logger;
    }

    /**
     * Starts the measurement daemon for the given measurement.
     *
     * @param Measurement $measurement
     * @return boolean
     */
    public function start(Measurement $measurement)
    {
        $result = $this->daemonCallForMeasurement($measurement, 'spawn');

        if (!isset($result['pid'])) {
            $this->logger->error('Daemon could not be started for measurement ' . $measurement->getId());
            return false;
        }

        $measurement->setPid($result['pid']);
        $this->em->persist($measurement);
        $this->em->flush();

        return true;
    }

    /**
     * Stops the measurementd eamon for the given measurement.
     *
     * @param Measurement $measurement
     * @return boolean
     */
    public function stop(Measurement $measurement)
    {
        $result = $this->daemonCallForMeasurement($measurement, 'kill');

        if (!isset($result['status']) || 200 !== $result['status']) {
            $this->logger->error('Daemon could not be stopped for measurement ' . $measurement->getId());
            return false;
        }

        $measurement->setPid(null);
        $this->em->persist($measurement);
        $this->em->flush();

        return true;
    }

    /**
     * Restarts the measurement daemon for the given measurement.
     *
     * @param Measurement $measurement
     * @return boolean
     */
    public function restart(Measurement $measurement)
    {
        $this->stop($measurement);
        return $this->start($measurement);
    }

    /**
     * Returns the process id for the given measurement.
     *
     * @param Measurement $measurement
     * @return int
     */
    public function getPIdForMeasurement(Measurement $measurement)
    {
        $result = $this->daemonCall('/list');
        $id = $measurement->getId();
        return isset($result['processes'][$id]) ? (int)$result['processes'][$id] : 0;
    }

    /**
     * Checks whether the daemon is available or not.
     *
     * @return bool
     */
    public function isAvailable()
    {
        $result = $this->daemonCall('');
        return (isset($result['status']) && 404 === $result['status']);
    }

    /**
     * Issues a daemon call for the given Measurement.
     *
     * @param Measurement $measurement
     * @param $action
     * @return mixed
     */
    private function daemonCallForMeasurement(Measurement $measurement, $action)
    {
        $action = '/' . $action . '?measurementId=' . (int)$measurement->getId();

        return $this->daemonCall($action);
    }

    /**
     * Issues a daemon call for the given action.
     *
     * @param $action
     * @return mixed
     */
    private function daemonCall($action)
    {
        $request = new Request('GET', $action, self::DAEMON_URL);

        $this->logger->debug(var_export($request, true));

        try {
            $response = $this->browser->send($request);
        } catch (RequestException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
            return null;
        }

        $this->logger->debug(var_export($response, true));

        return json_decode(
            $response->getContent(),
            true
        );
    }
} 