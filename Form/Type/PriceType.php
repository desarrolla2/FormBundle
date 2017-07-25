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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class PriceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'constraints' => [
                    new Range(['min' => 0]),
                ],
                'scale' => 2,
                'empty_data' => 0.0,
                'required' => true,
                'placeholder' => 'Price',
                'attr' => [
                    'class' => 'form-control',
                    'help' => 'Format 1234.56',
                ],
            ]
        );
    }

    public function getParent()
    {
        return NumberType::class;
    }
}
