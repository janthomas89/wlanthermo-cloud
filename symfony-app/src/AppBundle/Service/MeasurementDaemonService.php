<?php

namespace AppBundle\Service;

use AppBundle\Entity\Measurement;
use Buzz\Browser;
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
        $result = $this->daemonCall($measurement, 'spawn');

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
        $result = $this->daemonCall($measurement, 'kill');

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
     * Issues a daemon call for the given Measurement.
     *
     * @param Measurement $measurement
     * @param $action
     * @return mixed
     */
    private function daemonCall(Measurement $measurement, $action)
    {
        $action = '/' . $action . '?measurementId=' . (int)$measurement->getId();

        $request = new Request('GET', $action, self::DAEMON_URL);

        $this->logger->debug(var_export($request, true));

        $response = $this->browser->send($request);

        //@ToDo Error handling status 500? ...

        $this->logger->debug(var_export($response, true));

        return json_decode(
            $response->getContent(),
            true
        );
    }
} 