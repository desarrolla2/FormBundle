<?php

/*
 * This file is part of the Jotelulu package
 *
 * Copyright (c) 2017 Adder Global && Devtia
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Desarrolla2\FormBundle\Form\Validator;

use Symfony\Component\Validator\Constraints\File;

/**
 * @Annotation
 */
class FileExtension extends File
{
    public $message = 'Invalid extension';
    public $fileExtensions = [];
}
