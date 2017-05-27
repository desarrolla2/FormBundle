<?php

/*
 * This file is part of the Jotelulu package
 *
 * Copyright (c) 2017 Adder Global && Devtia
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Desarrolla2\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GbSizeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'scale' => 0,
                'empty_data' => 5,
                'required' => true,
                'placeholder' => 'Size',
                'attr' => [
                    'class' => 'form-control',
                ],
            ]
        );
    }

    public function getParent()
    {
        return IntegerType::class;
    }
}
