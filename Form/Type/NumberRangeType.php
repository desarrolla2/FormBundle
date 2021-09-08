<?php


namespace Desarrolla2\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NumberRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('start', $options['field_type'], array_merge(['required' => false], $options['field_options'], $options['field_options_start']));
        $builder->add('end', $options['field_type'], array_merge(['required' => false], $options['field_options'], $options['field_options_end']));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'field_options' => [],
                'field_options_start' => ['label' => 'From'],
                'field_options_end' => ['label' => 'To'],
                'field_type' => TextType::class,
            ]
        );
    }
}