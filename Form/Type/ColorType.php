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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ColorType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'required' => true,
                'placeholder' => 'Selecciona color',
                'constraints' => [
                    new Length(['min' => 3, 'max' => 7]),
                ],
                'attr' => [
                    'class' => 'form-control color-picker',
                ],
                'data' => '#000000',
            ]
        );
    }

    public function getParent()
    {
        return TextType::class;
    }
}
