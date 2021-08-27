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

use Desarrolla2\FormBundle\Form\Transformer\Utf8Transformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class TextAreaHtmlType extends AbstractType
{
    /** @var Utf8Transformer */
    private $utf8Transformer;

    public function __construct(Utf8Transformer $utf8Transformer = null)
    {
        $this->utf8Transformer = $utf8Transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->utf8Transformer && $options['transform_utf8']) {
            $builder->addModelTransformer($this->utf8Transformer);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'transform_utf8' => true,
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 20000]),
                ],
                'attr' => ['class' => 'ckeditor form-control'],
            ]
        );
    }

    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\TextareaType::class;
    }
}
