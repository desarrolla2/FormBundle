<?php

namespace Desarrolla2\FormBundle\Form\Sonata\Filter;

use Desarrolla2\FormBundle\Form\Type\NumberRangeType;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\DoctrineORMAdminBundle\Filter\Filter;

class NumberRangeFilter extends Filter
{
    protected $range = true;

    /**
     * {@inheritdoc}
     */
    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $data)
    {
        if (!$data || !is_array($data) || !array_key_exists('value', $data)) {
            return;
        }
        if ($this->range) {
            if (!array_key_exists('from', $data['value']) || !array_key_exists('to', $data['value'])) {
                return;
            }
            if (!$data['value']['from'] || !$data['value']['to']) {
                return;
            }
            $fromQuantity = $this->getNewParameterName($queryBuilder);
            $toQuantity = $this->getNewParameterName($queryBuilder);
            $this->applyWhere($queryBuilder, sprintf('%s.%s %s :%s', $alias, $field, '>=', $fromQuantity));
            $this->applyWhere($queryBuilder, sprintf('%s.%s %s :%s', $alias, $field, '<=', $toQuantity));
            $queryBuilder->setParameter($fromQuantity, $data['value']['from']);
            $queryBuilder->setParameter($toQuantity, $data['value']['to']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
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
}
