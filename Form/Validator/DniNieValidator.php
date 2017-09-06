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

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DniNieValidator extends ConstraintValidator
{
    const DNI_NIE_FORMAT = '/^[XYZ]?([0-9]{7,8})([A-Z])$/i';
    const LETTER_MAP = 'TRWAGMYFPDXBNJZSQVHLCKE';

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (strlen($value) != 9 || preg_match(self::DNI_NIE_FORMAT, $value, $matches) !== 1) {
            return false;
        }
        list(, $number, $letter) = $matches;

        return strtoupper($letter) === $this->calculateLetter($number);
    }

    /**
     * @param $number
     *
     * @return mixed
     */
    private function calculateLetter($number)
    {
        $map = self::LETTER_MAP;

        return $map[((int)$number) % 23];
    }
}
