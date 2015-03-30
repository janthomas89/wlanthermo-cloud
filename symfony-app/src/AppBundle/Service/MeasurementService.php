<?php

namespace AppBundle\Service;


use AppBundle\Entity\Device;
use AppBundle\Entity\Measurement;
use AppBundle\Entity\MeasurementProbe;
use AppBundle\Entity\MeasurementTimeSeries;
use AppBundle\Entity\MeasurementTimeSeriesRepository;
use AppBundle\Entity\Probe;
use AppBundle\Entity\TimeBoundArray;
use Doctrine\ORM\EntityManager;

/**
 * Class MeasurementService
 * Service class fot executing a measurement cycle.
 * @package AppBundle\Service
 */
class MeasurementService
{
    /** @var EntityManager */
    protected $em;

    /** @var MeasurementTimeSeriesRepository */
    protected $timeSeriesRepo;

    /** @var DeviceServiceInterface */
    protected $deviceService;

    /**
     * Instantiates the service.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, DeviceServiceInterface $deviceService)
    {
        $this->em = $em;
        $this->timeSeriesRepo = $em->getRepository('AppBundle:MeasurementTimeSeries');
        $this->deviceService = $deviceService;
    }

    /**
     * Executes one measurement cycle.
     *
     * @param Measurement $measurement
     */
    public function execute(Measurement $measurement)
    {
        /* Query measurement values */
        $device = $measurement->getDevice();
        $values = $this->queryValues($device);

        /** @var TimeBoundArray $tmpValues */
        foreach ($values as $tmpValues) {
            $date = $tmpValues->getTime();

            /** @var Probe $probe */
            foreach ($device->getProbes() as $probe) {
                $id = $probe->getId();
                $value = $tmpValues->get($id);

                /* Retrieve correct time series */
                $measurementProbe = $this->getMeasurementProbe($measurement, $probe);
                if (!$measurementProbe) {
                    continue;
                }

                $timeSeries = $this->getTimeSeries($measurementProbe, $date);
                $timeSeries->setMeasurementValue($date, $value);

                $this->em->persist($timeSeries);
                $this->em->flush();
                $this->em->detach($timeSeries);
            }
        }
    }

    /**
     * Queries the probe values for the given device.
     *
     * @param Device $device
     * @return mixed
     */
    protected function queryValues(Device $device)
    {
        return $this->deviceService->queryValues($device);
    }

    /**
     * Returns the matching measurement probe for the given probe.
     *
     * @param Measurement $measurement
     * @param Probe $probe
     * @return MeasurementProbe|null
     */
    protected function getMeasurementProbe(Measurement $measurement, Probe $probe)
    {
        /** @var MeasurementProbe $tmpProbe */
        foreach ($measurement->getProbes() as $tmpProbe) {
            if ($tmpProbe->getProbe()->getId() == $probe->getId()) {
                return $tmpProbe;
            }
        }

        return null;
    }

    /**
     * Retrieves a time series for the given probe and date.
     *
     * @param MeasurementProbe $measurementProbe
     * @param \DateTime $date
     * @return MeasurementTimeSeries
     */
    protected function getTimeSeries(MeasurementProbe $measurementProbe, \DateTime $date)
    {
        $series = $this->timeSeriesRepo->getTimeSeries($measurementProbe, $date);

        if (!$series) {
            $series = $this->timeSeriesRepo->createTimeSeries($measurementProbe, $date);
        }

        return $series;
    }
} 