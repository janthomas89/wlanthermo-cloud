<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MeasurementProbeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('channel', 'choice', array(
                'choices'   => range(0, 7),
                'required'  => true,
            ))
            ->add('type', 'choice', array(
                'choices'   => array(
                    1 => 'FANTAST',
                    2 => 'MAVERICK',
                    3 => 'ROSENSTEIN',
                    4 => 'ACURITE',
                    5 => 'ET-73',
                    6 => 'USER-1',
                    7 => 'USER-2',
                ),
                'required'  => true,
            ))
            ->add('name')
            ->add('color', new ColorType())
            //Probe
            //Min
            //Max
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MeasurementProbe'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_measurement_probe';
    }
}
