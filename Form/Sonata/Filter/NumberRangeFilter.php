<?php

namespace Desarrolla2\FormBundle\Form\Sonata\Filter;

use Desarrolla2\FormBundle\Form\Type\NumberRangeType;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\Type\Filter\NumberType;
use Sonata\DoctrineORMAdminBundle\Filter\Filter;

class NumberRangeFilter extends Filter
{
    protected $range = true;

    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $data)
    {
        if (!$data || !is_array($data) || !array_key_exists('value', $data)) {
            return;
        }
        $this->handle($queryBuilder, $alias, $field, $data, 'start', '>=');
        $this->handle($queryBuilder, $alias, $field, $data, 'end', '<=');
    }

    public function getDefaultOptions()
    {
        return [
            'input_type' => NumberType::class,
        ];
    }

    public function getRenderSettings()
    {
        return [
            NumberType::class,
            [
                'field_type' => $this->getFieldType(),
                'field_options' => $this->getFieldOptions(),
                'label' => $this->getLabel(),
            ],
        ];
    }

    public function getFieldType()
    {
        return $this->getOption('field_type', NumberRangeType::class);
    }

    private function handle(ProxyQueryInterface $queryBuilder, $alias, $field, $data, $arrayKey, $operator): void
    {
        if (!array_key_exists($arrayKey, $data['value'])) {
            return;
        }
        if ($data['value'][$arrayKey]) {
            $fromQuantity = $this->getNewParameterName($queryBuilder);
            $this->applyWhere($queryBuilder, sprintf('%s.%s %s :%s', $alias, $field, $operator, $fromQuantity));
            $queryBuilder->setParameter($fromQuantity, $data['value'][$arrayKey]);
        }
    }
}
