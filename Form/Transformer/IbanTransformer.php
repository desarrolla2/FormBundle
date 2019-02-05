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
        if (null === $value || '' === $value) {
            return;
        }
        $value = mb_strtoupper($value);
        $value = preg_replace("/[^\d\w]/", "", $value);
        $parts = [];
        while (strlen($value)) {
            $parts[] = substr($value, 0, 4);
            $value = substr($value, 4, strlen($value));
        }

        return implode(' ', $parts);
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
