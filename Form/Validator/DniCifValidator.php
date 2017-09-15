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

class DniCifValidator extends DniNieValidator
{
    /**
     * @var \Symfony\Component\Validator\Context\ExecutionContextInterface
     */
    protected $context;
    private $cifFormatExpr1 = '/^[ABEH][0-9]{8}$/i';
    private $cifFormatExpr2 = '/^[KPQS][0-9]{7}[A-J]$/i';
    private $cifFormatExpr3 = '/^[CDFGJLMNRUVW][0-9]{7}[0-9A-J]$/i';


    public function isValid($value)
    {
        if (!$value) {
            return true;
        }
        if (!$this->isValidNIF($value) && !$this->isValidNIE($value) && !$this->isValidCif(
                $value
            ) && !$this->isValidEuropeanVat($value)) {
            return false;
        }

        return true;
    }

    /**
     * @param $cif
     *
     * @return bool
     */
    protected function isValidCif($cif)
    {
        $cif = strtoupper($cif);
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
     * @param $number
     * @param $countryCode
     * @return bool
     */
    protected function isValidEuropeanVat($vatNumber)
    {
        $vatNumber = strtoupper(str_replace([' ', '.', '-', ',', ', '], '', trim($vatNumber)));
        $countryCode = substr($vatNumber, 0, 2);
        $vatNumber = substr($vatNumber, 2, strlen($vatNumber));

        $client = new \SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
        if (!$client) {
            throw  new \RuntimeException('Could not connect to VAT-VAT validation system.');
        }

        $params = ['countryCode' => $countryCode, 'vatNumber' => $vatNumber];

        try {
            $result = $client->checkVat($params);

            return (boolean)$result->valid;
        } catch (\SoapFault $e) {
        }

        return false;
    }
}
