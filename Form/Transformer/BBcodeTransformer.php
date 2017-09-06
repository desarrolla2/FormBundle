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

use JBBCode\Parser;
use Symfony\Component\Form\DataTransformerInterface;

class BBcodeTransformer implements DataTransformerInterface
{

    /** @var Parser */
    protected $parser;

    /**
     * @param $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param string $number
     *
     * @return string
     */
    public function transform($text)
    {
        if (null === $text) {
            return '';
        }

        return $text;
    }

    /**
     * @param string $number
     *
     * @return string
     */
    public function reverseTransform($text)
    {
        $this->parser->parse($text);
        $html = $this->parser->getAsHTML();

        dump($html);
        $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';

        return preg_replace($url, '<a href="http$2://$4" target="_blank" rel="nofollow" title="$0">$0</a>', $html);
    }
}
