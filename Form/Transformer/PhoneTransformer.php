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

namespace Desarrolla2\FormBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class PhoneTransformer implements DataTransformerInterface
{
    /** @var string $defaultRegion */
    protected $defaultRegion;

    protected $format;

    public function __construct($defaultRegion = 'ES', $format = \libphonenumber\PhoneNumberFormat::INTERNATIONAL)
    {
        $this->defaultRegion = $defaultRegion;
        $this->format = $format;
    }

    /**
     * @param mixed $value
     * @return bool|string
     */
    public function reverseTransform($value)
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $phoneNumber = $phoneUtil->parse($value, $this->defaultRegion);

        return $phoneUtil->format($phoneNumber, $this->format);
    }

    /**
     * @param mixed $number
     * @return mixed|string
     */
    public function transform($nass)
    {
        if (null === $nass) {
            return '';
        }

        return $nass;
    }
}
