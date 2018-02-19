<?php

/*
 * This file is part of the She Interlang package
 *
 * Copyright (c) 2017 Daniel González
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Daniel González <daniel@devtia.com>
 */

namespace Desarrolla2\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SelectAjaxType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'attr' => [
                    'class' => 'select2_ajax',
                ],
                'route' => '',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['route'] = $options['route'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAttribute('route', $options['route']);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
