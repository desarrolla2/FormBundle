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

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneValidator extends ConstraintValidator
{
    /**
     * @var \Symfony\Component\Validator\Context\ExecutionContextInterface
     */
    protected $context;

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }
        if (!$this->checkTelephone($value)) {
            $this->context->buildViolation($constraint->message)
                          ->addViolation();
        }
    }

    /**
     * @param string $telephone
     *
     * @return bool
     */
    private function checkTelephone($telephone)
    {
        $telephone = str_replace(' ', '', $telephone);
        $telephoneChars = str_split($telephone);

        if (empty($telephone)) {
            return false;
        }

        if (!in_array($telephoneChars[0], [6, 7, 8, 9])) {

            return false;
        }

        if (!preg_match('/^[\d]{9}$/', $telephone)) {


            return false;
        }

        return true;
    }
}
