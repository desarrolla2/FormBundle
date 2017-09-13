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
    /**
     * @var \Symfony\Component\Validator\Context\ExecutionContextInterface
     */
    protected $context;
    private $dniFormatExpr = '/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/';
    private $standardDniExpr = '/(^[0-9]{8}[A-Z]{1}$)/';
    private $availableLastChar = 'TRWAGMYFPDXBNJZSQVHLCKE';

    public function checkDni($dni)
    {
        $dni = strtoupper($dni);

        // Invalid format
        if (!$this->checkDniFormat($dni)) {
            return false;
        }

        return $this->checkStandardDni($dni) || $this->checkSpecialDni($dni);
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }
        if (!$this->checkDni($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

    private function isValid($number, $countryCode)
    {
        $vatNumber = str_replace([' ', '.', '-', ',', ', '], '', trim($number));

        $client = new \SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
        if (!$client) {
            throw  new \RuntimeException('No se ha podido conectar con el sistema de validación de VAT-IVA');
        }

        $params = ['countryCode' => $countryCode, 'vatNumber' => $vatNumber];

        try {
            $result = $client->checkVat($params);

            return (boolean)$result->valid;
        } catch (SoapFault $e) {
        }

        return false;
    }

    private function splitDni($dni)
    {
        return str_split($dni, 1);
    }

    protected function checkDniFormat($dni)
    {
        return preg_match($this->dniFormatExpr, $dni);
    }

    protected function checkSpecialDni($dni)
    {
        $dniCharacters = $this->splitDni($dni);

        $plus = $dniCharacters[2] + $dniCharacters[4] + $dniCharacters[6];
        for ($i = 1; $i < 8; $i += 2) {
            $plus += (int)substr((2 * $dniCharacters[$i]), 0, 1) + (int)substr((2 * $dniCharacters[$i]), 1, 1);
        }

        $n = 10 - substr($plus, strlen($plus) - 1, 1);

        return (preg_match('/^[KLM]{1}/', $dni)) ? ($dniCharacters[8] == chr(64 + $n) || $this->isValidDniLastChar(
                $dni
            )) : false;
    }

    protected function checkStandardDni($dni)
    {
        // Check if standard DNI
        return (preg_match($this->standardDniExpr, $dni)) ? $this->isValidDniLastChar($dni) : false;
    }

    protected function isValidDniLastChar($dni)
    {
        $dniCharacters = $this->splitDni($dni);

        return $dniCharacters[8] == substr($this->availableLastChar, substr($dni, 0, 8) % 23, 1);
    }
}
