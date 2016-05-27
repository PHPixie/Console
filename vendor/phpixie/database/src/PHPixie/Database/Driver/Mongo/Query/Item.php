<?php

namespace PHPixie\Database\Driver\Mongo\Query;

abstract class Item extends \PHPixie\Database\Driver\Mongo\Query
                    implements \PHPixie\Database\Driver\Mongo\Conditions\Builder
{
    public function __construct($connection, $parser, $builder)
    {
        parent::__construct($connection, $parser, $builder);

        $this->aliases = array_merge($this->aliases, array(
            'and' => '_and',
            'or'  => '_or',
            'xor' => '_xor',
            'not' => '_not',
        ));
    }
    
    protected function addContainerOperatorCondition($logic, $negate, $field, $operator, $values, $containerName = null)
    {
        $this->builder->addOperatorCondition($logic, $negate, $field, $operator, $values, $containerName);
        
        return $this;
    }
    
    protected function addContainerInOperatorCondition($field, $values, $logic, $negate, $containerName = null)
    {
        $this->builder->addInOperatorCondition($field, $values, $logic, $negate, $containerName);

        return $this;
    }

    protected function buildContainerCondition($logic, $negate, $args, $containerName = null)
    {
        $this->builder->buildCondition($logic, $negate, $args, $containerName);

        return $this;
    }
    
    protected function addContainerCondition($logic, $negate, $condition, $containerName = null)
    {
        $this->builder->addCondition($logic, $negate, $condition, $containerName);

        return $this;
    }
    
    protected function startContainerConditionGroup($logic = 'and', $negate = false, $containerName = null)
    {
        $this->builder->startConditionGroup($logic, $negate, $containerName);

        return $this;
    }

    protected function startContainerSubdocumentConditionGroup($field, $logic = 'and', $negate = false, $containerName = null)
    {
        $this->builder->startSubdocumentConditionGroup($field, $logic, $negate, $containerName);

        return $this;
    }
    
    protected function startContainerSubarrayItemConditionGroup($field, $logic = 'and', $negate = false, $containerName = null)
    {
        $this->builder->startSubarrayItemConditionGroup($field, $logic, $negate, $containerName);

        return $this;
    }
    
    protected function endContainerConditionGroup($containerName = null)
    {
        $this->builder->endConditionGroup($containerName);

        return $this;
    }
    
    protected function addContainerPlaceholder($logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return $this->builder->addPlaceholder($logic, $negate, $allowEmpty, $containerName);
    }
    
    protected function addContainerSubdocumentPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return $this->builder->addSubdocumentPlaceholder($field, $logic, $negate, $allowEmpty, $containerName);
    }
    
    protected function addContainerSubarrayItemPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return $this->builder->addSubarrayItemPlaceholder($field, $logic, $negate, $allowEmpty, $containerName);
    }

    public function addCondition($logic, $negate, $condition)
    {
        $this->builder->addCondition($logic, $negate, $condition);
        return $this;
    }

    public function buildCondition($logic, $negate, $params)
    {
        $this->builder->buildCondition($logic, $negate, $params);
        return $this;
    }
    
    public function buildWhereCondition($logic, $negate, $condition)
    {
        return $this->buildContainerCondition($logic, $negate, $condition, 'where');
    }
    
    public function addWhereCondition($logic, $negate, $container)
    {
        return $this->addContainerCondition($logic, $negate, $container, 'where');
    }
    
    public function getWhereContainer()
    {
        return $this->builder->conditionContainer('where');
    }

    public function getWhereConditions()
    {
        return $this->builder->getConditions('where');
    }
    
    public function addOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        return $this->addContainerOperatorCondition($logic, $negate, $field, $operator, $values);
    }
    
    public function addWhereOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        return $this->addContainerOperatorCondition($logic, $negate, $field, $operator, $values, 'where');
    }
    
    public function addInOperatorCondition($field, $values, $logic = 'and', $negate = false)
    {
        return $this->addContainerInOperatorCondition($field, $values, $logic, $negate);
    }

    public function addWhereInOperatorCondition($field, $values, $logic = 'and', $negate = false)
    {
        return $this->addContainerInOperatorCondition($field, $values, $logic, $negate, 'where');
    }

    public function startConditionGroup($logic = 'and', $negate = false)
    {
        return $this->startContainerConditionGroup($logic, $negate);
    }
    
    public function startWhereConditionGroup($logic = 'and', $negate = false)
    {
        return $this->startContainerConditionGroup($logic, $negate, 'where');
    }
    
    public function startSubdocumentConditionGroup($field, $logic = 'and', $negate = false)
    {
        return $this->startContainerSubdocumentConditionGroup($field, $logic, $negate);
    }
    
    public function startWhereSubdocumentConditionGroup($field, $logic = 'and', $negate = false)
    {
        return $this->startContainerSubdocumentConditionGroup($field, $logic, $negate, 'where');
    }
    
    public function startSubarrayItemConditionGroup($field, $logic = 'and', $negate = false)
    {
        return $this->startContainerSubarrayItemConditionGroup($field, $logic, $negate);
    }
    
    public function startWhereSubarrayItemConditionGroup($field, $logic = 'and', $negate = false)
    {
        return $this->startContainerSubarrayItemConditionGroup($field, $logic, $negate, 'where');
    }

    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerPlaceholder($logic, $negate, $allowEmpty);
    }
    
    public function addWherePlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerPlaceholder($logic, $negate, $allowEmpty, 'where');
    }
    
    public function addSubdocumentPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerSubdocumentPlaceholder($field, $logic, $negate, $allowEmpty);
    }
    
    public function addWhereSubdocumentPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerSubdocumentPlaceholder($field, $logic, $negate, $allowEmpty, 'where');
    }
    
    public function addSubarrayItemPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerSubarrayItemPlaceholder($field, $logic, $negate, $allowEmpty);
    }
    
    public function addWhereSubarrayItemPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerSubarrayItemPlaceholder($field, $logic, $negate, $allowEmpty, 'where');
    } 

    public function where()
    {
        return $this->buildContainerCondition('and', false, func_get_args(), 'where');
    }

    public function andWhere()
    {
        return $this->buildContainerCondition('and', false, func_get_args(), 'where');
    }

    public function orWhere()
    {
        return $this->buildContainerCondition('or', false, func_get_args(), 'where');
    }

    public function xorWhere()
    {
        return $this->buildContainerCondition('xor', false, func_get_args(), 'where');
    }

    public function whereNot()
    {
        return $this->buildContainerCondition('and', true, func_get_args(), 'where');
    }

    public function andWhereNot()
    {
        return $this->buildContainerCondition('and', true, func_get_args(), 'where');
    }

    public function orWhereNot()
    {
        return $this->buildContainerCondition('or', true, func_get_args(), 'where');
    }

    public function xorWhereNot()
    {
        return $this->buildContainerCondition('xor', true, func_get_args(), 'where');
    }

    public function startWhereGroup()
    {
        return $this->startContainerConditionGroup('and', false, 'where');
    }

    public function startAndWhereGroup()
    {
        return $this->startContainerConditionGroup('and', false, 'where');
    }

    public function startOrWhereGroup()
    {
        return $this->startContainerConditionGroup('or', false, 'where');
    }

    public function startXorWhereGroup()
    {
        return $this->startContainerConditionGroup('xor', false, 'where');
    }

    public function startWhereNotGroup()
    {
        return $this->startContainerConditionGroup('and', true, 'where');
    }

    public function startAndWhereNotGroup()
    {
        return $this->startContainerConditionGroup('and', true, 'where');
    }

    public function startOrWhereNotGroup()
    {
        return $this->startContainerConditionGroup('or', true, 'where');
    }

    public function startXorWhereNotGroup()
    {
        return $this->startContainerConditionGroup('xor', true, 'where');
    }

    public function endWhereGroup()
    {
        return $this->endContainerConditionGroup('where');
    }

    public function _and()
    {
        return $this->buildContainerCondition('and', false, func_get_args());
    }

    public function _or()
    {
        return $this->buildContainerCondition('or', false, func_get_args());
    }

    public function _xor()
    {
        return $this->buildContainerCondition('xor', false, func_get_args());
    }

    public function _not()
    {
        return $this->buildContainerCondition('and', true, func_get_args());
    }

    public function andNot()
    {
        return $this->buildContainerCondition('and', true, func_get_args());
    }

    public function orNot()
    {
        return $this->buildContainerCondition('or', true, func_get_args());
    }

    public function xorNot()
    {
        return $this->buildContainerCondition('xor', true, func_get_args());
    }

    public function startGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    public function startAndGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    public function startOrGroup()
    {
        return $this->startConditionGroup('or', false);
    }

    public function startXorGroup()
    {
        return $this->startConditionGroup('xor', false);
    }

    public function startNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    public function startAndNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    public function startOrNotGroup()
    {
        return $this->startConditionGroup('or', true);
    }

    public function startXorNotGroup()
    {
        return $this->startConditionGroup('xor', true);
    }


    
    public function startSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'and', false);
    }

    public function startAndSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'and', false);
    }

    public function startOrSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'or', false);
    }

    public function startXorSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'xor', false);
    }

    public function startNotSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'and', true);
    }

    public function startAndNotSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'and', true);
    }

    public function startOrNotSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'or', true);
    }

    public function startXorNotSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'xor', true);
    }
    
    public function startSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'and', false);
    }

    public function startAndSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'and', false);
    }

    public function startOrSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'or', false);
    }

    public function startXorSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'xor', false);
    }

    public function startNotSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'and', true);
    }

    public function startAndNotSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'and', true);
    }

    public function startOrNotSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'or', true);
    }

    public function startXorNotSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'xor', true);
    }
    
    
    
    public function startWhereSubdocumentGroup($field)
    {
        return $this->startWhereSubdocumentConditionGroup($field, 'and', false);
    }

    public function startAndWhereSubdocumentGroup($field)
    {
        return $this->startWhereSubdocumentConditionGroup($field, 'and', false);
    }

    public function startOrWhereSubdocumentGroup($field)
    {
        return $this->startWhereSubdocumentConditionGroup($field, 'or', false);
    }

    public function startXorWhereSubdocumentGroup($field)
    {
        return $this->startWhereSubdocumentConditionGroup($field, 'xor', false);
    }

    public function startWhereNotSubdocumentGroup($field)
    {
        return $this->startWhereSubdocumentConditionGroup($field, 'and', true);
    }

    public function startAndWhereNotSubdocumentGroup($field)
    {
        return $this->startWhereSubdocumentConditionGroup($field, 'and', true);
    }

    public function startOrWhereNotSubdocumentGroup($field)
    {
        return $this->startWhereSubdocumentConditionGroup($field, 'or', true);
    }

    public function startXorWhereNotSubdocumentGroup($field)
    {
        return $this->startWhereSubdocumentConditionGroup($field, 'xor', true);
    }
    
    public function startWhereSubarrayItemGroup($field)
    {
        return $this->startWhereSubarrayItemConditionGroup($field, 'and', false);
    }

    public function startAndWhereSubarrayItemGroup($field)
    {
        return $this->startWhereSubarrayItemConditionGroup($field, 'and', false);
    }

    public function startOrWhereSubarrayItemGroup($field)
    {
        return $this->startWhereSubarrayItemConditionGroup($field, 'or', false);
    }

    public function startXorWhereSubarrayItemGroup($field)
    {
        return $this->startWhereSubarrayItemConditionGroup($field, 'xor', false);
    }

    public function startWhereNotSubarrayItemGroup($field)
    {
        return $this->startWhereSubarrayItemConditionGroup($field, 'and', true);
    }

    public function startAndWhereNotSubarrayItemGroup($field)
    {
        return $this->startWhereSubarrayItemConditionGroup($field, 'and', true);
    }

    public function startOrWhereNotSubarrayItemGroup($field)
    {
        return $this->startWhereSubarrayItemConditionGroup($field, 'or', true);
    }

    public function startXorWhereNotSubarrayItemGroup($field)
    {
        return $this->startWhereSubarrayItemConditionGroup($field, 'xor', true);
    }    
    
    public function endGroup()
    {
        return $this->endContainerConditionGroup();
    }
}
