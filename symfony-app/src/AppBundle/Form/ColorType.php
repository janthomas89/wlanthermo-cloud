<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;

class ColorType extends AbstractType
{
    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'color';
    }
}