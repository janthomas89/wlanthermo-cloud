<?php

namespace AppBundle\Constraint;


use AppBundle\Entity\Device;
use AppBundle\Entity\Measurement;
use AppBundle\Entity\MeasurementRepository;
use AppBundle\Service\DeviceAPIServiceInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class DeviceInUseConstraintValidator
 * @package AppBundle\Constraint
 */
class DeviceInUseConstraintValidator extends ConstraintValidator
{
    /** @var MeasurementRepository */
    protected $measurementRepo;

    /**
     * Instantiates the service.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->measurementRepo = $em->getRepository('AppBundle:Measurement');
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var Measurement $measurement */
        $measurement = $value;
        $device = $measurement->getDevice();

        if ($this->measurementRepo->inUse($device)) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
} 