<?php
namespace PHPixie\Tests\Database\Query\Implementation;

/**
 * @coversDefaultClass \PHPixie\Database\Query\Implementation\Builder
 */
abstract class BuilderTest extends \PHPixie\Tests\AbstractDatabaseTest
{
    protected $conditionsMock;
    protected $valuesMock;
    
    protected $containers;
    protected $builder;
    
    public function setUp()
    {
        $this->containers = array(
            $this->container(),
            $this->container(),
        );
        
        $this->conditionsMock = $this->conditions();
        $this->valuesMock = $this->quickMock('\PHPixie\Database\Values', array());
        
        $this->builder = $this->builder();
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addFields
     */
    public function testAddFields()
    {
        $builder = $this->builder;
        $builder->addFields(array('test'));
        $builder->addFields(array(array('pixie', 'fairy' => 'test')));
        $this->assertEquals(array(
            'test',
            'pixie',
        ), $builder->getArray('fields'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::setOffset
     */
    public function testSetOffset()
    {
        $builder = $this->builder;
        $builder->setOffset(6);
        
        $this->assertDatabaseException(function() use($builder){
            $builder->setOffset('t');
        });
        
        $this->assertEquals(6, $builder->getValue('offset'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::setLimit
     */
    public function testSetLimit()
    {
        $builder = $this->builder;
        $builder->setLimit(6);
        
        $this->assertDatabaseException(function() use($builder){
            $builder->setLimit('t');
        });
        
        $this->assertEquals(6, $builder->getValue('limit'));
    }

    /**
     * @covers ::<protected>
     * @covers ::addOrderAscendingBy
     * @covers ::addOrderDescendingBy
     */
    public function testOrderBy()
    {
        $expected = array(
            $this->addOrderBy('test', 'asc'),
            $this->addOrderBy('pixie', 'asc'),
            $this->addOrderBy('trixie', 'desc'),
            $this->addOrderBy('test', 'desc'),
        );
        
        $this->assertEquals($expected, $this->builder->getArray('orderBy'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addSet
     */
    public function testAddSet()
    {
        $builder = $this->builder;
        $builder->addSet(array('test', 'pixie'));
        $builder->addSet(array(array('trixie' => 'fairy', 'test2' => 5)));
        $builder->addSet(array('test2', 6));
        
        $this->assertDatabaseException(function() use($builder){
            $builder->addSet(array('t'));
        });
        
        $this->assertDatabaseException(function() use($builder){
            $builder->addSet(array(array('t')));
        });
        
        $this->assertDatabaseException(function() use($builder){
            $builder->addSet('t');
        });
        
        $this->assertEquals(array(
            'test'   => 'pixie',
            'trixie' => 'fairy',
            'test2'  => 6
        ), $builder->getArray('set'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::setData
     */
    public function testSetData()
    {
        $builder = $this->builder;
        $builder->setData(array('f' => 1));
        
        $this->assertDatabaseException(function() use($builder){
            $builder->setData('t');
        });
        
        $this->assertEquals(array('f' => 1), $builder->getValue('data'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::clearValue
     * @covers ::getValue
     */
    public function testGetClearValue()
    {
        $this->assertEquals(null, $this->builder->getValue('limit'));
        $this->builder->setLimit(5);
        $this->assertEquals(5, $this->builder->getValue('limit'));
        $this->builder->clearValue('limit');
        $this->assertEquals(null, $this->builder->getValue('limit'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::clearArray
     * @covers ::getArray
     */
    public function testGetClearArray()
    {
        $this->assertEquals(array(), $this->builder->getArray('fields'));
        $this->builder->addFields(array('test'));
        $this->assertEquals(array('test'), $this->builder->getArray('fields'));
        $this->builder->clearArray('fields');
        $this->assertEquals(array(), $this->builder->getArray('fields'));
    }
    
    /**
     * @covers ::conditionContainer
     */
    public function testConditionContainer()
    {
        $this->prepareContainer();
        $this
            ->conditionsMock
            ->expects($this->at(1))
            ->method('container')
            ->will($this->returnValue($this->containers[1]));
        
        $firstContainer = $this->builder->conditionContainer('first');
        $secondContainer = $this->builder->conditionContainer('second');
        $this->assertEquals($firstContainer, $this->builder->conditionContainer('first'));
        $this->assertEquals($firstContainer, $this->builder->conditionContainer());
        $this->assertEquals($secondContainer, $this->builder->conditionContainer('second'));
        $this->assertEquals($secondContainer, $this->builder->conditionContainer());
        $this->assertNotSame($firstContainer, $secondContainer);
    }
    
    /**
     * @covers ::conditionContainer
     */
    public function testConditionContainerDefault()
    {
        $container = $this->builder->conditionContainer();
        $this->assertSame($container, $this->builder->conditionContainer('where'));
    }
    
    /**
     * @covers ::getConditions
     */
    public function testGetConditions()
    {
        $this->prepareContainer();
        $this->assertEquals(array(), $this->builder->getConditions('first'));
        
        $firstContainer = $this->builder->conditionContainer('first');
        
        $firstContainer
            ->expects($this->at(0))
            ->method('getConditions')
            ->will($this->returnValue(array()));
                           
        $this->assertEquals(array(), $this->builder->getConditions('first'));
                        
        $firstContainer
            ->expects($this->at(0))
            ->method('getConditions')
            ->will($this->returnValue(array(1)));
                           
        $this->assertEquals(array(1), $this->builder->getConditions('first'));
        
    }
    
    /**
     * @covers ::addCondition
     */
    public function testAddCondition()
    {
        $this->prepareContainer();
        $condition = $this->quickMock('\PHPixie\ORM\Conditions\Condition', array());
        $this->expectCalls($this->containers[0], array('addCondition' => array('or', true, $condition)));
        $this->builder->addCondition('or', true, $condition);
    }
    
    /**
     * @covers ::buildCondition
     */
    public function testBuildCondition()
    {
        $this->prepareContainer();
        $this->expectCalls($this->containers[0], array('buildCondition' => array('or', true, array(5))));
        $this->builder->buildCondition('or', true, array(5), 'first');
    }
    
    /**
     * @covers ::addOperatorCondition
     */
    public function testAddOperatorCondition()
    {
        $this->prepareContainer();
        $this->expectCalls($this->containers[0], array('addOperatorCondition' => array('or', true, 'age', '>', array(5))));
        $this->builder->addOperatorCondition('or', true, 'age', '>', array(5), 'first');
    }
    
    /**
     * @covers ::addPlaceholder
     */
    public function testAddPlaceholder()
    {
        $this->prepareContainer();
        $this->expectCalls($this->containers[0], array('addPlaceholder' => array('or', true, false)));
        $this->builder->addPlaceholder('or', true, false, 'first');
    }
    
    /**
     * @covers ::startConditionGroup
     */
    public function testStartConditionGroup()
    {
        $this->prepareContainer();
        $this->expectCalls($this->containers[0], array('startConditionGroup' => array('or', true)));
        $this->builder->startConditionGroup('or', true, 'first');
    }
    
    /**
     * @covers ::endConditionGroup
     */
    public function testEndConditionGroup()
    {
        $this->prepareContainer();
        $this->expectCalls($this->containers[0], array('endGroup' => array()));
        $this->builder->endConditionGroup('first');
    }
    
    /**
     * @covers ::assert
     */
    public function testAssert()
    {
        $this->builder->assert(true, 'test');
        try{
            $this->builder->assert(false, 'test');
        }catch(\PHPixie\Database\Exception\Builder $e){
            $this->assertEquals('test', $e->getMessage());
        }
    }
    
    protected function addOrderBy($field, $dir)
    {
        $orderBy = $this->quickMock('\PHPixie\Database\Values\OrderBy', array());
        
        $this->valuesMock
                ->expects($this->at(0))
                ->method('orderBy')
                ->with($field, $dir)
                ->will($this->returnValue($orderBy));
        
        if($dir === 'asc') {
            $this->builder->addOrderAscendingBy($field);
        }else{
            $this->builder->addOrderDescendingBy($field);
        }
        
        return $orderBy;
    }
    
    protected function assertDatabaseException($callback)
    {
        $except = false;
        try{
            $callback();
        }catch(\PHPixie\Database\Exception\Builder $e){
            $except = true;
        }
        
        $this->assertEquals(true, $except);
    }
    
    protected function prepareContainer()
    {
        $this->conditionsMock
                ->expects($this->at(0))
                ->method('container')
                ->will($this->returnValue($this->containers[0]));
    }
    
    protected function getDriver()
    {
        return $this->quickMock('\PHPixie\Database\Driver', array('valuesData'));
    }
    
    protected function conditions()
    {
        return $this->quickMock('\PHPixie\Database\Conditions', array('container'));
    }
    
    protected function container()
    {
        return $this->quickMock('\PHPixie\Database\Conditions\Builder\Container', array());
    }
    
    abstract protected function builder();
}
