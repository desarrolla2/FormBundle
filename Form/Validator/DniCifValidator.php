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

class DniCifValidator extends ConstraintValidator
{
    private $dniFormatExpr = '/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/';
    private $standardDniExpr = '/(^[0-9]{8}[A-Z]{1}$)/';
    private $availableLastChar = 'TRWAGMYFPDXBNJZSQVHLCKE';
    private $cifFormatExpr1 = '/^[ABEH][0-9]{8}$/i';
    private $cifFormatExpr2 = '/^[KPQS][0-9]{7}[A-J]$/i';
    private $cifFormatExpr3 = '/^[CDFGJLMNRUVW][0-9]{7}[0-9A-J]$/i';

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
        if (!$this->checkDni($value) && !$this->checkCif($value)) {
            $this->context->buildViolation($constraint->message)
                          ->addViolation();
        }
    }

    private function splitDni($dni)
    {
        return str_split($dni, 1);
    }

    protected function checkDniFormat($dni)
    {
        return preg_match($this->dniFormatExpr, $dni);
    }

    protected function isValidDniLastChar($dni)
    {
        $dniCharacters = $this->splitDni($dni);

        return $dniCharacters[8] == substr($this->availableLastChar, substr($dni, 0, 8) % 23, 1);
    }

    protected function checkStandardDni($dni)
    {
        // Check if standard DNI
        return (preg_match($this->standardDniExpr, $dni)) ? $this->isValidDniLastChar($dni) : false;
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
     * @param $cif
     *
     * @return bool
     */
    protected function checkCifFormat($cif)
    {
        if (preg_match($this->cifFormatExpr1, $cif) || preg_match($this->cifFormatExpr2, $cif) || preg_match(
                $this->cifFormatExpr3,
                $cif
            )
        ) {
            $control = $cif[strlen($cif) - 1];
            $suma_A = 0;
            $suma_B = 0;

            for ($i = 1; $i < 8; ++$i) {
                if ($i % 2 == 0) {
                    $suma_A += intval($cif[$i]);
                } else {
                    $t = (intval($cif[$i]) * 2);
                    $p = 0;

                    for ($j = 0; $j < strlen($t); ++$j) {
                        $p += substr($t, $j, 1);
                    }
                    $suma_B += $p;
                }
            }

            $suma_C = (intval($suma_A + $suma_B)).'';
            $suma_D = (10 - intval($suma_C[strlen($suma_C) - 1])) % 10;

            $letras = 'JABCDEFGHI';

            if ($control >= '0' && $control <= '9') {
                return $control == $suma_D;
            } else {
                return strtoupper($control) == $letras[$suma_D];
            }
        } else {
            return false;
        }
    }

    /**
     * @param $cif
     *
     * @return bool
     */
    public function checkCif($cif)
    {
        $cif = strtoupper($cif);

        return $this->checkCifFormat($cif);
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
}
