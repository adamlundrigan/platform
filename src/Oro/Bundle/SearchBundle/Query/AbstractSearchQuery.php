<?php

namespace Oro\Bundle\SearchBundle\Query;

use Doctrine\Common\Collections\Expr\Expression;

use Oro\Bundle\SearchBundle\Query\Criteria\Criteria;

abstract class AbstractSearchQuery implements SearchQueryInterface
{
    const WHERE_AND = 'and';
    const WHERE_OR  = 'or';

    /**
     * @var Query
     */
    protected $query;

    /**
     * @var Result
     */
    protected $result;

    /**
     * Performing an internal query() to the engine, without
     * data postprocessing etc.
     *
     * @return mixed
     */
    abstract protected function query();

    /**
     * Getting the results from the query() and caching them.
     *
     * @return mixed|Result
     */
    public function getResult()
    {
        if (!$this->result) {
            $this->result = $this->query();
        }

        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return $this->getResult()->getElements();
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param Query $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstResult($firstResult)
    {
        $this->query->getCriteria()->setFirstResult($firstResult);
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstResult()
    {
        return $this->query->getCriteria()->getFirstResult();
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxResults($maxResults)
    {
        $this->query->getCriteria()->setMaxResults($maxResults);
    }

    /**
     * {@inheritdoc}
     */
    public function getMaxResults()
    {
        return $this->query->getCriteria()->getMaxResults();
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalCount()
    {
        return $this->getResult()->getRecordsCount();
    }

    /**
     * {@inheritdoc}
     */
    public function getSortBy()
    {
        $orderings = $this->query->getCriteria()->getOrderings();

        if (empty($orderings)) {
            return null;
        }

        $orders    = array_keys($orderings);
        $fieldName = array_pop($orders);

        return $fieldName === null ? null : Criteria::explodeFieldTypeName($fieldName)[1];
    }

    /**
     * {@inheritdoc}
     */
    public function getSortOrder()
    {
        $orders = $this->query
            ->getCriteria()
            ->getOrderings();

        if (empty($orders)) {
            return null;
        }

        return array_pop($orders);
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderBy($fieldName, $direction = Query::ORDER_ASC, $type = Query::TYPE_TEXT)
    {
        $field = $type . '.' . $fieldName;

        $this->query
            ->getCriteria()
            ->orderBy([$field => $direction]);
    }

    /**
     * {@inheritdoc}
     */
    public function addSelect($fieldName, $enforcedFieldType = null)
    {
        return $this->query->addSelect($fieldName, $enforcedFieldType);
    }

    /**
     * {@inheritdoc}
     */
    public function setFrom($entities)
    {
        return $this->query->from($entities);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function addWhere(Expression $expression, $type = self::WHERE_AND);

    /**
     * {@inheritdoc}
     */
    public function getSelectAliases()
    {
        return $this->query->getSelectAliases();
    }
}
