<?php

namespace AppBundle\Service;


use AppBundle\Entity\Device;
use AppBundle\Entity\Measurement;
use AppBundle\Entity\MeasurementProbe;
use AppBundle\Entity\MeasurementTimeSeries;
use AppBundle\Entity\MeasurementTimeSeriesRepository;
use AppBundle\Entity\Probe;
use AppBundle\Entity\TimeBoundArray;
use AppBundle\Util\LowPassFilter;
use Doctrine\ORM\EntityManager;

/**
 * Class MeasurementService
 * Service class fot executing a measurement cycle.
 * @package AppBundle\Service
 */
class MeasurementService
{
    /** @const int Time constant for the low pass filter */
    const RC = 18;

    /** @var EntityManager */
    private $em;

    /** @var MeasurementTimeSeriesRepository */
    private $timeSeriesRepo;

    /** @var DeviceAPIServiceInterface */
    private $deviceService;

    /** @var array */
    private $lowPassFilters = [];

    /**
     * Instantiates the service.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, DeviceAPIServiceInterface $deviceService)
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

                if ($value != MeasurementTimeSeriesRepository::NOT_A_TEMPERATURE
                    && !$timeSeries->hasMeasurementValue($date)
                ) {
                    $filtered = $this->applyLowPassFilter($value, $date, $probe);
                    $timeSeries->setMeasurementValue($date, $filtered);
                }

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
    private function queryValues(Device $device)
    {
        return $this->deviceService->queryValues($device);
    }

    /**
     * Applies a low pass filter for the given value, date and probe.
     *
     * @param float $value
     * @param \DateTime $time
     * @param Probe $probe
     * @return float
     */
    private function applyLowPassFilter($value, \DateTime $time, Probe $probe)
    {
        $filter = $this->getLowPassFilter($probe);
        return $filter->apply($value, $time);
    }

    /**
     * Returns a low pass filter for the given probe.
     *
     * @param Probe $probe
     * @return LowPassFilter
     */
    private function getLowPassFilter(Probe $probe)
    {
        $key = $probe->getId();

        if (!isset($this->lowPassFilters[$key])) {
            $this->lowPassFilters[$key] = new LowPassFilter(self::RC);
        }

        return $this->lowPassFilters[$key];
    }

    /**
     * Returns the matching measurement probe for the given probe.
     *
     * @param Measurement $measurement
     * @param Probe $probe
     * @return MeasurementProbe|null
     */
    private function getMeasurementProbe(Measurement $measurement, Probe $probe)
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
    private function getTimeSeries(MeasurementProbe $measurementProbe, \DateTime $date)
    {
        $series = $this->timeSeriesRepo->getTimeSeries($measurementProbe, $date);

        if (!$series) {
            $series = $this->timeSeriesRepo->createTimeSeries($measurementProbe, $date);
        }

        return $series;
    }

    /**
     * Returns the current snapshot of the given measurement. This
     * includes the current temperatues, alerts and history data.
     *
     * @param Measurement $measurement
     * @return array
     */
    public function getSnapshot(Measurement $measurement)
    {
        $result = [];

        $result['deviceStatus'] = $this->getDeviceStatus($measurement);

        $lastMeasurement = $this->timeSeriesRepo->getLastMeasurement($measurement);
        $result['lastMeasurement'] = $lastMeasurement ? $lastMeasurement->getTimestamp() : 0;
        $result['lastMeasurementFormatted'] = $lastMeasurement ? $lastMeasurement->format('d.m.Y H:i:s') : '';

        $current = $this->getCurrentValues($measurement);
        $result['current'] = $current;

        $alerts = $this->getAlerts($measurement, $current);
        $result['alerts'] = $alerts;

        $fullHistory = $this->timeSeriesRepo->getFullHistory($measurement);
        $result['last20min'] = $this->aggregateLast20Min($measurement, $fullHistory);

        $result['fullHistory'] = $this->aggregateFullHistory($measurement, $fullHistory);

        $result['active'] = $measurement->isActive();

        return $result;
    }

    /**
     * Retrieves the device status.
     *
     * @param Measurement $measurement
     * @return bool
     */
    private function getDeviceStatus(Measurement $measurement)
    {
        $device = $measurement->getDevice();
        return $this->deviceService->checkConnectivity($device)
            && $this->deviceService->checkAuthentication($device);
    }

    /**
     * Returns the current temperature values.
     *
     * @param Measurement $measurement
     * @return array
     */
    private function getCurrentValues(Measurement $measurement)
    {
        return $this->timeSeriesRepo->getCurrentValues($measurement);
    }

    /**
     * Calculates the alters based on the current temperature values.
     *
     * @param Measurement $measurement
     * @param array $current
     * @return array
     */
    private function getAlerts(Measurement $measurement, array $current)
    {
        $alerts = [];

        /** @var MeasurementProbe $probe */
        foreach ($measurement->getProbes() as $probe) {
            $c = $current[$probe->getId()];
            $a = $c < $probe->getMin() || $c > $probe->getMax();
            $alerts[$probe->getId()] = $a;
        }

        return $alerts;
    }

    /**
     * Aggregates the last 20 minutes.
     *
     * @param Measurement $measurement
     * @param array $history
     * @return array
     */
    private function aggregateLast20Min(Measurement $measurement, array $history)
    {
        $axis = ['x'];
        $last20min = [$axis];

        $threshold = $measurement->isActive() ? new \DateTime() : clone $measurement->getEnd();
        $threshold->modify('-20 minutes');

        /** @var MeasurementProbe $probe */
        foreach ($measurement->getProbes() as $probe) {
            $probeHistory = $history[$probe->getId()];
            $tmpLast20min = [$probe->getName()];

            /** @var MeasurementTimeSeries $timeSeries */
            foreach($probeHistory as $timeSeries) {
                $axis[] = $timeSeries->getTime()->getTimestamp() * 1000;
                $tmpLast20min[] = $timeSeries->getAvg();

                if ($timeSeries->getTime() < $threshold) {
                    break;
                }
            }

            $last20min[] = $tmpLast20min;
        }

        $last20min[0] = array_unique($axis);

        return $last20min;
    }

    /**
     * Aggregates the full measurements history.
     *
     * @param Measurement $measurement
     * @param array $history
     * @return array
     */
    private function aggregateFullHistory(Measurement $measurement, array $history)
    {
        $axis = ['x'];
        $result = [$axis];

        $duration = $measurement->getDurationInMinutes();

        /** @var MeasurementProbe $probe */
        foreach ($measurement->getProbes() as $probe) {
            $probeHistory = $history[$probe->getId()];
            $tmpResult = [$probe->getName()];

            /** @var MeasurementTimeSeries $timeSeries */
            foreach($probeHistory as $timeSeries) {
                $time = $timeSeries->getTime();

                if ($this->shouldSkip($duration, $time)) {
                    $axis[] = $time->getTimestamp() * 1000;
                    $tmpResult[] = $timeSeries->getAvg();
                }
            }

            $result[] = $tmpResult;
        }

        $result[0] = array_unique($axis);

        return $result;
    }

    /**
     * Decides wether the values should be skipped or not.
     *
     * @param int $duration Duration in minutes
     * @param \DateTime $time
     * @return bool
     */
    private function shouldSkip($duration, \DateTime $time)
    {
        $modulus = max(1, min(30, ceil($duration / 40)));
        return 0 === ($time->format('i') % $modulus);
    }
}
