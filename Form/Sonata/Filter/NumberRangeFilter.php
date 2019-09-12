<?php

namespace Desarrolla2\FormBundle\Form\Sonata\Filter;

use Desarrolla2\FormBundle\Form\Type\NumberRangeType;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\DoctrineORMAdminBundle\Filter\Filter;

class NumberRangeFilter extends Filter
{
    protected $range = true;

    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $data)
    {
        if (!$data || !is_array($data) || !array_key_exists('value', $data)) {
            return;
        }
        $this->handle($queryBuilder, $alias, $field, $data, 'from', '>=');
        $this->handle($queryBuilder, $alias, $field, $data, 'to', '<=');
    }

    public function getDefaultOptions()
    {
        return [];
    }

    public function getRenderSettings()
    {
        return [
            'sonata_type_filter_default',
            [
                'field_type' => NumberRangeType::class,
                'field_options' => $this->getFieldOptions(),
                'operator_type' => 'hidden',
                'operator_options' => [],
                'label' => $this->getLabel(),
            ],
        ];
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
