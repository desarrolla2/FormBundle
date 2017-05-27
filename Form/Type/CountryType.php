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

use FormBundle\Entity\Country;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryType extends AbstractType
{
    /** @var EntityManager */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'choices' => $this->getCountryNames(),
                'data' => $this->getDefaultCountry(),
                'attr' => ['class' => 'select2'],
            ]
        );
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    private function getDefaultCountry()
    {
        /** @var Country $country */
        $country = $this->em->getRepository('FormBundle:Country')->findOneBy(['code' => 'ES']);
        if ($country) {
            return $country->getId();
        }

        return null;
    }

    private function getCountryNames()
    {
        $countries = $this->em->getRepository('FormBundle:Country')->findAll();
        $data = [];
        foreach ($countries as $country) {
            if (!$country->getName()) {
                continue;
            }
            $data[$country->getName()] = $country->getId();
        }

        return $data;
    }
}
