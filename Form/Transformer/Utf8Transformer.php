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

use ForceUTF8\Encoding;
use Symfony\Component\Form\DataTransformerInterface;

class Utf8Transformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     * @return bool|string
     */
    public function reverseTransform($value)
    {
        return trim(Encoding::fixUTF8($value));
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
