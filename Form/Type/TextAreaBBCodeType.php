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
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class TextAreaBBCodeType extends AbstractType
{
    private $transformer;

    public function __construct(DataTransformerInterface $bbCodeTransformer)
    {
        $this->transformer = $bbCodeTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'constraints' => [
                    new Length(['min' => 3, 'max' => 5000]),
                ],
                'attr' => ['class' => 'bbcode-editor form-control'],
            ]
        );
    }

    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\TextareaType::class;
    }
}
