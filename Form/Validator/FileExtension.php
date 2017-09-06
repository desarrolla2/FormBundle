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
