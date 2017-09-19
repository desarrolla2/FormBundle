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

class SpanishNassTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $nass
     * @return bool|string
     */
    public function reverseTransform($value)
    {
        $nass = preg_replace('[\D]', '', $value);
        if (strlen($nass) != 12) {
            return $value;
        }
        $province = substr($nass, 0, 2);
        $number = substr($nass, 2, 8);
        $control = substr($nass, 10, 2);
        if ($number < 10000000) {
            $nd = $number + $province * 10000000;
        } else {
            $nd = $province.$number;
        }

        $validation = $nd % 97;
        if ($validation != $control) {
            return $value;
        }

        return sprintf('%s/%s/%s', $province, $number, $control);
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
