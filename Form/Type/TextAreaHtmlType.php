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

use Exercise\HTMLPurifierBundle\Form\HTMLPurifierTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TextAreaHtmlType extends AbstractType
{
    /** @var HTMLPurifierTransformer */
    private $purifierTransformer;

    /**
     * TextAreaHtmlType constructor.
     * @param DataTransformerInterface $purifierTransformer
     */
    public function __construct(DataTransformerInterface $purifierTransformer)
    {
        $this->purifierTransformer = $purifierTransformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this->purifierTransformer);
        $builder->addModelTransformer($this->purifierTransformer);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 5000]),
                ],
                'attr' => ['class' => 'ckeditor form-control'],
            ]
        );
    }

    /**
     * @return null|string
     */
    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\TextareaType::class;
    }
}
