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

class ClearNotDigitsTransformer implements DataTransformerInterface
{
    public function reverseTransform($number)
    {
        return $this->transform($number);
    }

    public function transform($number)
    {
        return preg_replace("/[^\d]/", "", $number);
    }
}
