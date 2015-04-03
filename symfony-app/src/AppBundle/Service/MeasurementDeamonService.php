<?php

namespace AppBundle\Service;

use AppBundle\Entity\Measurement;
use Buzz\Browser;
use Buzz\Message\Request;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

/**
 * Class MeasurementDeamonService
 * Service class for interacting with the measurement deamon.
 *
 * @package AppBundle\Service
 */
class MeasurementDeamonService
{
    /** URL for the measurement deamon */
    const DEAMON_URL = 'http://localhost:1025';

    /** @var EntityManager */
    protected $em;

    /** @var Browser */
    protected $browser;

    /** @var LoggerInterface */
    protected $logger;

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
     * Starts the measurement deamon for the given measurement.
     *
     * @param Measurement $measurement
     * @return boolean
     */
    public function start(Measurement $measurement)
    {
        $result = $this->deamonCall($measurement, 'spawn');

        if (!isset($result['pid'])) {
            $this->logger->error('Deamon could not be started for measurement ' . $measurement->getId());
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
        $result = $this->deamonCall($measurement, 'kill');

        if (!isset($result['status']) || 200 === $result['status']) {
            $this->logger->error('Deamon could not be stopped for measurement ' . $measurement->getId());
            return false;
        }

        $measurement->setPid(null);
        $this->em->persist($measurement);
        $this->em->flush();

        return true;
    }

    /**
     * Restarts the measurement deamon for the given measurement.
     *
     * @param Measurement $measurement
     * @return boolean
     */
    public function restart(Measurement $measurement)
    {
        return $this->stop($measurement)
            && $this->start($measurement);
    }

    /**
     * Issues a deamon call for the given Measurement.
     *
     * @param Measurement $measurement
     * @param $action
     * @return mixed
     */
    protected function deamonCall(Measurement $measurement, $action)
    {
        $action = '/' . $action . '?measurementId=' . (int)$measurement->getId();

        $request = new Request('GET', $action, self::DEAMON_URL);

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