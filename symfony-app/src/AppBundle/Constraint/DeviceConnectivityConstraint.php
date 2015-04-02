<?php

namespace AppBundle\Constraint;


use Symfony\Component\Validator\Constraint;

/**
 * Class DeviceConnectivityConstraint
 * @package AppBundle\Validator\Constraint
 * @Annotation
 */
class DeviceConnectivityConstraint extends Constraint
{
    public $message = 'WNo connection to the device API could not be established. Please check the URL and the connectivity.';

    public function validatedBy()
    {
        return 'device_connectivity_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
