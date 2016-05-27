<?php

namespace PHPixie\Database\Type\SQL\Query;

interface Items extends \PHPixie\Database\Type\SQL\Query,
                        \PHPixie\Database\Query\Items,
                        \PHPixie\Database\Type\SQL\Conditions\Builder
{
    public function join($table, $alias = null, $type = 'inner');
    public function clearJoins();
    public function getJoins();

    public function buildOnCondition($logic, $negate, $args);
    public function addOnCondition($logic, $negate, $condition);
    public function addOnOperatorCondition($logic, $negate, $field, $operator, $values);
    public function addOnInOperatorCondition($field, $values, $logic = 'and', $negate = false);
    public function addOnPlaceholder($logic = 'and', $negate = false, $allowEmpty = true);
    public function startOnConditionGroup($logic = 'and', $negate = false);
    
    public function on();
    public function andOn();
    public function orOn();
    public function xorOn();
    public function onNot();
    public function andOnNot();
    public function orOnNot();
    public function xorOnNot();
    public function startOnGroup();
    public function startAndOnGroup();
    public function startOrOnGroup();
    public function startXorOnGroup();
    public function startOnNotGroup();
    public function startAndOnNotGroup();
    public function startOrOnNotGroup();
    public function startXorOnNotGroup();
    public function endOnGroup();
}
