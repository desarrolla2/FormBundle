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

class IbanTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $nass
     * @return bool|string
     */
    public function reverseTransform($value)
    {
        $iban = preg_replace("/[^\d\w]/", "", $value);
        $items = [
            substr($iban, 0, 4),
            substr($iban, 4, 4),
            substr($iban, 8, 4),
            substr($iban, 12, 4),
            substr($iban, 16, 4),
            substr($iban, 20, 4),
        ];

        return implode(' ', $items);
    }

    /**
     * @param mixed $number
     * @return mixed|string
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        return $value;
    }
}
