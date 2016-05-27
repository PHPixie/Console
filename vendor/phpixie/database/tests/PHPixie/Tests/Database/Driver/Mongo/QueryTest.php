<?php
namespace PHPixie\Tests\Database\Driver\Mongo;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query
 */
abstract class QueryTest extends \PHPixie\Tests\Database\Query\ImplementationTest
{
    protected $queryClass;
    
    protected function getConnection()
    {
        return $this->connection = $this->quickMock('\PHPixie\Database\Driver\Mongo\Connection', array('run'));
    }

    protected function query()
    {
        $class = $this->queryClass;
        return new $class($this->connection, $this->parser, $this->builder);
    }

    protected function getParser()
    {
        return $this->quickMock('\PHPixie\Database\Driver\Mongo\Parser', array('parse'), array());
    }

    protected function getBuilder()
    {
        return $this->getMockBuilder('\PHPixie\Database\Driver\Mongo\Query\Builder')
            ->disableOriginalConstructor(true)
            ->getMock();

    }
    
    /**
     * @covers ::collection
     * @covers ::clearCollection
     * @covers ::getCollection
     */
    public function testGetSetCollection()
    {
        $this->setClearGetTest('collection', array(
            array(array('pixie')),
        ));
    }

    /**
     * @covers ::parse
     */
    public function testParse()
    {
        $this->parserTest();
    }
    
    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $query = $this->query();
        $runner = new \PHPixie\Database\Driver\Mongo\Query\Runner();
        $this->parser
                ->expects($this->any())
                ->method('parse')
                ->with ($query)
                ->will($this->returnValue($runner));

        $this->connection
                ->expects($this->once())
                ->method('run')
                ->will($this->returnValue('a'));

        $this->assertEquals('a', $query->execute());
    }
}
