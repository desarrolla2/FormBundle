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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class PasswordType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'constraints' => [
                    new Length(['min' => 6, 'max' => 50]),
                    new Regex(['pattern' => '#\d+#', 'message' => 'Must contain at least one number']),
                    new Regex(['pattern' => '#[A-Z]+#', 'message' => 'Must contain at least one uppercase letter']),
                    new Regex(['pattern' => '#[a-z]+#', 'message' => 'Must contain at least one lowercase letter']),
                ],
            ]
        );
    }

    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\PasswordType::class;
    }
}
