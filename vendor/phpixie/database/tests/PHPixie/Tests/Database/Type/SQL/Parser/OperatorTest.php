<?php
namespace PHPixie\Tests\Database\Type\SQL\Parser;

/**
 * @coversDefaultClass \PHPixie\Database\Type\SQL\Parser\Operator
 */
abstract class OperatorTest extends \PHPixie\Tests\Database\Parser\OperatorTest
{
    protected $database;
    protected $operatorParser;

    public function setUp()
    {
        $this->database = new \PHPixie\Database(null);
    }

    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testExceptions()
    {
        foreach ($this->exceptionConditions() as $condition) {
            $except = false;
            try {
                $this->operatorParser->parse($condition);
            } catch (\PHPixie\Database\Exception\Parser $e) {
                $except = true;
            }
            $this->assertEquals(true, $except);
        }
    }

    protected function exceptionConditions()
    {
        $conditions = array(
            $this->operator('a', '=', array(1, 2)),
            $this->operator('a', '=*', null),
            $this->operator('a', '=*', array(1,2)),
            $this->operator('a', 'like', array('hello',1)),
            $this->operator('a', 'regexp', array('hello',1)),
            $this->operator('a', 'in', array('one')),
            $this->operator('a', 'between', array(1)),
        );

        return $conditions;
    }

    protected function conditions()
    {
        $conditions = array(
            $this->operator('a', '=', array(1)),
            $this->operator('a', '=', array($this->database->sqlExpression('la'))),
            $this->operator('a', '=*', array($this->database->sqlExpression('la'))),
            $this->operator('a', '=*', array('b')),
            $this->operator('a', '=', array(null)),
            $this->operator('a', '!=', array(null)),
            $this->operator('a', '!=', array(1)),
            $this->operator('a', '!=*', array('c.b')),
            $this->operator('a', '>', array(1)),
            $this->operator('a', '>*', array('b')),
            $this->operator('a', 'like', array('hello')),
            $this->operator('a', 'regexp', array('hello')),
            $this->operator('a', 'in', array(array(1, 2))),
            $this->operator('a', 'in', array($this->database->sqlExpression('la'))),
            $this->operator('a', 'in', array($this->queryStub('fairy',array(1)))),
            $this->operator('a', 'between', array(1, 2)),
            $this->operator('a', 'not between', array(1, 2)),
            $this->operator('a.b', '=', array($this->database->sqlExpression('b', array(1)))),
            $this->operator($this->database->sqlExpression('a + b'), '=', array(1))
        );

        return $conditions;
    }

    protected function operator($field, $operator, $values)
    {
        return new \PHPixie\Database\Conditions\Condition\Field\Operator($field, $operator, $values);
    }

    protected function queryStub($sql, $params = array())
    {
        $query = $this->getMock('\PHPixie\Database\Driver\PDO\Query\Type\Select', array('parse'), array(), '', false);
        $query
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($this->database->sqlExpression($sql, $params)));

        return $query;
    }

}
