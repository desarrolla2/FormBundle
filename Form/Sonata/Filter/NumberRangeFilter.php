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
        $this->handleFrom($queryBuilder, $alias, $field, $data);
        $this->handleTo($queryBuilder, $alias, $field, $data);
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

    private function handleFrom(ProxyQueryInterface $queryBuilder, $alias, $field, $data): void
    {
        if (!array_key_exists('from', $data['value'])) {
            return;
        }
        if ($data['value']['from']) {
            $fromQuantity = $this->getNewParameterName($queryBuilder);
            $this->applyWhere($queryBuilder, sprintf('%s.%s %s :%s', $alias, $field, '>=', $fromQuantity));
            $queryBuilder->setParameter($fromQuantity, $data['value']['from']);
        }
    }

    private function handleTo(ProxyQueryInterface $queryBuilder, $alias, $field, $data): void
    {
        if (!array_key_exists('to', $data['value'])) {
            return;
        }
        if ($data['value']['to']) {
            $toQuantity = $this->getNewParameterName($queryBuilder);
            $this->applyWhere($queryBuilder, sprintf('%s.%s %s :%s', $alias, $field, '<=', $toQuantity));
            $queryBuilder->setParameter($toQuantity, $data['value']['to']);
        }
    }
}
