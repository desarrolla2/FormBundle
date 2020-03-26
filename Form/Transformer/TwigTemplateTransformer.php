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

class TwigTemplateTransformer implements DataTransformerInterface
{
    public function reverseTransform($value)
    {
        $value = trim(Encoding::fixUTF8($value));
        $value = htmlentities($value, null, 'utf-8');
        $value = str_replace('&nbsp;', ' ', $value);
        $value = urldecode($value);
        $value = html_entity_decode($value);
        $value = preg_replace(['#{{[\s]+#', '#[\s]+}}#'], ['{{', '}}'], $value);

        dump($value);

        return $value;
    }

    public function transform($value)
    {

        return $this->reverseTransform($value);
    }
}
