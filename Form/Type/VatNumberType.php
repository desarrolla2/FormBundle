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

use Desarrolla2\FormBundle\Form\Validator\DniCif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VatNumberType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'trim' => 'true',
                'label' => 'VAT-IVA',
                'required' => false,
                'constraints' => [
                    new DniCif(),
                ],
            ]
        );
    }

    public function getParent()
    {
        return TextType::class;
    }
}
