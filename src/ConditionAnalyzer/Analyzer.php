<?php

namespace BaoPham\DynamoDb\ConditionAnalyzer;

use BaoPham\DynamoDb\ComparisonOperator;
use BaoPham\DynamoDb\DynamoDbModel;
use BaoPham\DynamoDb\H;

/**
 * Class ConditionAnalyzer
 * @package BaoPham\DynamoDb\ConditionAnalyzer
 *
 * Usage:
 *
 * $analyzer = with(new Analyzer)
 *  ->on($model)
 *  ->withIndex($index)
 *  ->analyze($conditions);
 *
 * $analyzer->isExactSearch();
 * $analyzer->keyConditions();
 * $analyzer->filterConditions();
 * $analyzer->index();
 */
class Analyzer
{
    /**
     * @var DynamoDbModel
     */
    private $model;

    /**
     * @var array
     */
    private $conditions = [];

    /**
     * @var string
     */
    private $indexName;

    public function on(DynamoDbModel $model)
    {
        $this->model = $model;

        return $this;
    }

    public function withIndex($index)
    {
        $this->indexName = $index;

        return $this;
    }

    public function analyze($conditions)
    {
        $this->conditions = $conditions;

        return $this;
    }

    public function isExactSearch()
    {
        if (empty($this->conditions)) {
            return false;
        }

        if (empty($this->identifierConditions())) {
            return false;
        }

        foreach ($this->conditions as $condition) {
            if (array_get($condition, 'type') !== ComparisonOperator::EQ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return Index|null
     */
    public function index()
    {
        return $this->getIndex();
    }

    public function keyConditions()
    {
        $index = $this->getIndex();

        if ($index) {
            return $this->getConditions($index->columns());
        }

        return $this->identifierConditions();
    }

    public function filterConditions()
    {
        $keyConditions = $this->keyConditions() ?: [];

        return array_filter($this->conditions, function ($condition) use ($keyConditions) {
            return array_search($condition, $keyConditions) === false;
        });
    }

    public function identifierConditions()
    {
        $keyNames = $this->model->getKeyNames();

        $conditions = $this->getConditions($keyNames);

        if (!$this->hasValidQueryOperator(...$keyNames)) {
            return null;
        }

        return $conditions;
    }

    public function identifierConditionValues()
    {
        $idConditions = $this->identifierConditions();

        if (!$idConditions) {
            return [];
        }

        $values = [];

        foreach ($idConditions as $condition) {
            $values[$condition['column']] = $condition['value'];
        }

        return $values;
    }

    /**
     * @param $column
     *
     * @return array
     */
    

    /**
     * @param $columns
     *
     * @return array
     */
    

    /**
     * @return Index|null
     */
    

    
}
