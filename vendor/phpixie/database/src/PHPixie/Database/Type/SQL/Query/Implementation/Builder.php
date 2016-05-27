<?php

namespace PHPixie\Database\Type\SQL\Query\Implementation;

abstract class Builder extends \PHPixie\Database\Query\Implementation\Builder
{
    protected $joins = array();

    public function addFields($args)
    {
        $this->addKeyValuesToArray('fields', $args, false, false);
    }

    public function setTable($table, $alias)
    {
        $this->setValue('table', array(
            'table' => $table,
            'alias' => $alias
        ));
    }

    public function addJoin($table, $alias, $type)
    {
        $this->addToArray('joins', array(
            'container' => $this->conditions->container('=*'),
            'table' => $table,
            'alias' => $alias,
            'type'  => $type
        ));
    }

    public function addGroupBy($args)
    {
        $this->addValuesToArray('groupBy', $args);
    }

    public function addUnion($query, $all)
    {
        $this->addToArray('unions', array('query' => $query, 'all' => $all));
    }

    public function addIncrement($args)
    {
        $this->addKeyValuesToArray('increment', $args, true, true, true);
    }

    public function setBatchData($columns, $rows)
    {
        $this->setValue('batchData', array('columns' => $columns, 'rows' => $rows));
    }
    
    public function addInOperatorCondition($field, $values, $logic, $negate, $containerName)
    {
        $this->conditionContainer($containerName)->addInOperatorCondition($field, $values, $logic, $negate);
    }

    protected function lastOnContainer()
    {
        $joins = $this->getArray('joins');
        $this->assert(!empty($joins), "Cannot add join conditions as no joins have been added to the query.");
        $join = end($joins);
        $container = $join['container'];
        $this->defaultContainer = $container;

        return $container;
    }

    public function addOnCondition($logic, $negate, $condition)
    {
        $this->lastOnContainer()->addCondition($logic, $negate, $condition);
    }
    
    public function buildOnCondition($logic, $negate, $args)
    {
        $this->lastOnContainer()->buildCondition($logic, $negate, $args);
    }

    public function startOnConditionGroup($logic, $negate)
    {
        $this->lastOnContainer()->startConditionGroup($logic, $negate);
    }

    public function endOnConditionGroup()
    {
        $this->lastOnContainer()->endGroup();
    }
    
    public function addOnOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        $this->lastOnContainer()->addOperatorCondition($logic, $negate, $field, $operator, $values);
    }
    
    public function addOnInOperatorCondition($field, $values, $logic, $negate)
    {
        $this->lastOnContainer()->addInOperatorCondition($field, $values, $logic, $negate);
    }
    
    public function addOnPlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        $this->lastOnContainer()->addPlaceholder($logic, $negate, $allowEmpty);
    }
    
    

}
