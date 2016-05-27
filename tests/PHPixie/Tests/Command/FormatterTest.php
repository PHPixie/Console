<?php

namespace PHPixie\Tests\Command;

class FormatterTest extends \PHPixie\Test\Testcase
{
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new \PHPixie\Command\Formatter();
    }

    public function testUsage()
    {
        $options = array(
            $this->option('c', true, true),
            $this->option('b', true, false),
            $this->option('a', true, false),
            $this->option('trixie', true, true),
            $this->option('d', false, false),
            $this->option('pixie', false, false),
            $this->option('e', false, false),
            $this->option('v', false, true),
        );

        $options = array_reverse($options);

        $arguments = array(
            $this->argument('fairy', true, false),
            $this->argument('blum', false, true),
        );

        $result = $this->formatter->usage('test', $options, $arguments);
        $this->assertSame(
            'test -c -a=VALUE -b=VALUE --trixie [ -v ] [ -d=VALUE ] [ -e=VALUE ] [ --pixie=VALUE ] FAIRY [ BLUM... ]',
            $this->formatter->usage('test', $options, $arguments)
        );
    }

    protected function option($name, $isRequired, $isFlag)
    {
        return new \PHPixie\Command\Parameter\Option($name, $isRequired, $isFlag);
    }

    protected function argument($name, $isRequired, $isArray)
    {
        return new \PHPixie\Command\Parameter\Argument($name, $isRequired, $isArray);
    }
}
