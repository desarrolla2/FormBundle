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

class VatIvaValidator extends ConstraintValidator
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
        if (!$this->isValid($value)) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }

    public function isValid($number, $countryCode)
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
        } catch (\SoapFault $e) {
        }

        return false;
    }
}
