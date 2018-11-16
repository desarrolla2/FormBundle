<?php

/*
 * This file is part of the Form Bundle package
 *
 * Copyright (c) 2017 Daniel González
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Daniel González <daniel@desarrolla2.com>
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
                'grouping' => true,
                'scale' => 2,
                'empty_data' => 0.0,
                'required' => true,
                'placeholder' => 'Price',
                'attr' => [
                    'class' => 'form-control',
                    'help' => 'Format 1,234.56',
                ],
            ]
        );
    }

    public function getParent()
    {
        return NumberType::class;
    }
}
