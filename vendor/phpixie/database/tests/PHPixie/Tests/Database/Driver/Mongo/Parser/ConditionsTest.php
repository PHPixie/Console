<?php

namespace PHPixie\Tests\Database\Driver\Mongo\Parser;

if(!class_exists('\MongoId'))
    require_once(__DIR__.'/OperatorTestFiles/MongoId.php');

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Parser\Conditions
 */
class ConditionsTest extends \PHPixie\Tests\AbstractDatabaseTest
{
    protected $conditionsParser;
    protected $mongoId = '49a702d5450046d3d515d10d';
    
    protected function setUp()
    {
        $this->database = new \PHPixie\Database(null);
        $operatorParser = new \PHPixie\Database\Driver\Mongo\Parser\Operator();
        $driver = $this->database->driver('mongo');
        $this->conditionsParser = new \PHPixie\Database\Driver\Mongo\Parser\Conditions(
            $driver,
            $driver->conditions(),
            $operatorParser
        );
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testParseSimple()
    {
        $container = $this->getContainer()
                                    ->_and('name', 1)
                                    ->_and(function ($container) {
                                        $container->_and('id', 2)->_or('id', 3);
                                    });
        $this->assertGroup($container, array(
            '$or'=>array(
                array('name'=>1, 'id'=>2),
                array('name' => 1, 'id' => 3)
            )
        ));
        
        $container = $this->getContainer()
            ->_and('b', 1)
            ->startGroup()
            ->endGroup();

        $this->assertGroup($container, array(
            'b' => 1
        ));
        
        $container = $this->getContainer()
                                    ->_and('a', 1)
                                    ->_and('a', '>', 6)
                                    ->_and('a', '<', 10);

        $this->assertGroup($container, array(
            '$and'=>array(
                array('a' => 1),
                array('a' => array('$gt' => 6)),
                array('a' => array('$lt'=>10))
            )
        ));

        $container = $this->getContainer()
                                    ->_and('a', 1)
                                    ->_and(function ($container) {
                                        $container
                                            ->_and('b', '>', 2)
                                            ->_xor('c', '>' , 3);
                                    });

        $this->assertGroup($container, array(
            '$or' => array(
                        array(
                            'a' => 1,
                            'b' => array('$gt' => 2),
                            'c' => array('$lte' => 3),
                        ),
                        array(
                            'a' => 1,
                            'b' => array('$lte' => 2),
                            'c' => array('$gt' => 3),
                        )
                    )
        ));

        $container = $this->getContainer()
                                    ->_and('b', 1)
                                    ->_and('b', '>', 2)
                                    ->_and('a', 2)
                                    ->_and('a', '<', 3)
                                    ->andNot('a', '>', 4);

        $this->assertGroup($container, array(
            '$and' => array(
                        array(
                            'b' => 1,
                            'a' => 2
                        ),
                        array(
                            'b' => array('$gt' => 2),
                            'a' => array('$lt' => 3),
                        ),
                        array(
                            'a' => array('$lte' => 4),
                        )
                    )
        ));

        $container = $this->getContainer()
                                    ->_and('b', 1)
                                    ->xorNot('a', '>', 4);

        $this->assertGroup($container, array(
            '$or' => array(
                        array(
                            'b' => 1,
                            'a' => array('$gt' => 4)
                        ),
                        array(
                            'b' => array('$ne' => 1),
                            'a' => array('$lte' => 4),
                        )
                    )
        ));

        $container = $this->getContainer()->_and(function ($container) {
            $container->_and(function () {});
        });
        $this->assertGroup($container, array());

        $container = $this->getContainer()->_and(function ($container) {
            $container
                    ->_and('a', 1)
                    ->_and(function ($container) {
                        $container->_and(function () {});
                    });
        });
        $this->assertGroup($container, array('a' => 1));

    }
    
    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testParseConditionException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Parser');
        $this->conditionsParser->parse(array(new \stdClass()));
    }
    
    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testParseMongoId()
    {
        $mongoId = new \MongoId($this->mongoId);
        $container = $this->getContainer()
                                    ->_and('_id', $this->mongoId);
        
        $this->assertGroup($container, array(
            '_id' => $mongoId
        ));
        
        $container = $this->getContainer()
            ->startSubarrayItemGroup('a')
                ->_and('_id', 1)
            ->endGroup();
        
        $this->assertGroup($container, array(
            'a' => array(
                '$elemMatch' => (object) array(
                    '_id' => 1
                )
            )
        ));
    }

    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testParseNegate()
    {
        $container = $this->getContainer()
                                    ->_and('a', 1)
                                    ->andNot('c',2);
        $this->assertGroup($container, array(
            'a' => 1,
            'c' => array('$ne'=>2)
        ));
    }

    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testParsePrecedance()
    {
        $container = $this->getContainer()
                                    ->_and('a', 1)
                                    ->_and('b', 1)
                                    ->_or('c', 1)
                                    ->_and('d', 1);

        $this->assertGroup($container, array(
            '$or' => array(
                        array(
                            'a' => 1,
                            'b' => 1
                        ),
                        array(
                            'c' => 1,
                            'd' => 1,
                        )
                    )
        ));

        $container = $this->getContainer()
                                    ->_and('d', 1)
                                    ->_or('a', 1)
                                    ->_and('b', 1)
                                    ->_xor('c', 1)
                                    ->_or('e',1);

        $this->assertGroup($container, array(
            '$or' => array(
                        array('d' => 1),
                        array(
                            'a' => 1,
                            'b' => 1,
                            'c' => array('$ne' => 1)
                        ),
                        array(
                            'a' => array('$ne' => 1),
                            'c' => 1
                        ),
                        array(
                            'b' => array('$ne' => 1),
                            'c' => 1
                        ),
                        array('e' => 1),
                    )
        ));

        $container = $this->getContainer()
                                    ->_and('a', 1);
        $placeholder = $container->addPlaceholder('and');
        $placeholder
                ->_and('b',1)
                ->_or('c', 1);

        $this->assertGroup($container, array(
            '$or' => array(
                        array('a' => 1, 'b' => 1),
                        array('a' => 1, 'c' => 1)
                    )
        ));
    }

    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testParseSubdocuments()
    {
        $container = $this->getContainer()
            ->startSubdocumentConditionGroup('a')
                ->startSubdocumentConditionGroup('b')
                    ->_and('c', 1)
                ->endGroup()
            ->endGroup();

        $this->assertGroup($container, array(
            'a.b.c' => 1
        ));
        
        $container = $this->getContainer()
            ->startSubarrayItemGroup('a')
                ->startSubdocumentConditionGroup('b')
                    ->_and('c', 1)
                ->endGroup()
            ->endGroup();

        $this->assertGroup($container, array(
            'a' => array (
                '$elemMatch' => (object) array (
                    'b.c' => 1,
                ),
            )
        ));
        
        $container = $this->getContainer()
            ->startSubdocumentConditionGroup('a')
                ->startGroup()
                    ->_and('b', 1)
                    ->startGroup()
                        ->_and('c', 1)
                        ->startSubarrayItemConditionGroup('d', 'or')
                            ->startSubdocumentConditionGroup('e')
                                ->_and('f', 1)
                            ->endGroup()
                        ->endGroup()
                    ->endGroup()
                ->endGroup()
            ->endGroup();

        $this->assertGroup($container, array(
            '$or' => array (
                array (
                    'a.b' => 1,
                    'a.c' => 1
                ),
                
                array (
                    'a.b' => 1,
                    'a.d' => array (
                            '$elemMatch' => (object) array (
                                    'e.f' => 1
                            )
                    )

                )

            )
        ));
        
    }
    
    protected function getContainer()
    {
        $container = $this->database->driver('mongo')->conditions()->container();

        return $container;
    }

    protected function assertGroup($container, $expect)
    {
        $parsed = $this->conditionsParser->parse($container->getConditions());
        $this->assertSameGroup($parsed, $this->conditionsParser->parse($container->getConditions()));
        $this->assertSameGroup($expect, (array) $parsed);
    }
    
    protected function assertSameGroup($left, $right)
    {
        
        $this->assertEquals(gettype($left), gettype($right));

        if($left instanceof \MongoId) {
            $this->assertInstanceOf('\MongoId', $right);
            $this->assertEquals((string) $left, (string) $right);
            return;
        }
        
        if(is_object($left)) {
            $left = (array) $left;
            $right = (array) $right;
        }
        
        if(!is_array($left)) {
            $this->assertEquals($left, $right);    
            return;
        }
        
        $this->assertEquals(array_keys($left), array_keys($right));
        
        foreach($left as $key => $value) {
            $this->assertSameGroup($value, $right[$key]);
        }
    }
    

}
