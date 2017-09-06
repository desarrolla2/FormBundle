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

class SpanishNassValidator extends ConstraintValidator
{
    /**
     * @var \Symfony\Component\Validator\Context\ExecutionContextInterface
     */
    protected $context;

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }
        if (!$this->checkSpanishNass($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

    /**
     * @param string $telephone
     *
     * @return bool
     */
    private function checkSpanishNass($nass)
    {
        $nass = preg_replace('[\D]', '', $nass);
        if (strlen($nass) != 12) {
            return false;
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
            return false;
        }

        return true;
    }
}
