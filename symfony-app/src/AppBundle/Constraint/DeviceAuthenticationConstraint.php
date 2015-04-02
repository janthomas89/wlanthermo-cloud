<?php

namespace AppBundle\Constraint;


use Symfony\Component\Validator\Constraint;

/**
 * Class DeviceAuthenticationConstraint
 * @package AppBundle\Validator\Constraint
 * @Annotation
 */
class DeviceAuthenticationConstraint extends Constraint
{
    public $message = 'With the given credentials no connection to the device API could not be established. Please check your username and password.';

    public function validatedBy()
    {
        return 'device_authentication_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
