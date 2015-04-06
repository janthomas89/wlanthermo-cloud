<?php

namespace AppBundle\Constraint;


use Symfony\Component\Validator\Constraint;

/**
 * Class DeviceInUseConstraint
 * @package AppBundle\Validator\Constraint
 * @Annotation
 */
class DeviceInUseConstraint extends Constraint
{
    public $message = 'Device is already in use.';

    public function validatedBy()
    {
        return 'device_in_use_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
