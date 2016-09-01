<?php

namespace PHPixie\Tests\Console;

class FormatterTest extends \PHPixie\Test\Testcase
{
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new \PHPixie\Console\Formatter();
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
        
        $config = $this->quickMock('\PHPixie\Console\Command\Config');
        
        $this->method($config, 'getName', 'test');
        $this->method($config, 'getOptions', $options);
        $this->method($config, 'getArguments', $arguments);

        $this->assertSame(
            'test -c -a=VALUE -b=VALUE --trixie [ -v ] [ -d=VALUE ] [ -e=VALUE ] [ --pixie=VALUE ] FAIRY [ BLUM... ]',
            $this->formatter->usage($config)
        );
    }

    protected function option($name, $isRequired, $isFlag)
    {
        $option = $this->quickMock('\PHPixie\Console\Command\Config\Option');
        
        $this->method($option, 'name', $name);
        $this->method($option, 'isRequired', $isRequired);
        $this->method($option, 'isFlag', $isFlag);
        $this->method($option, 'isShort', strlen($name) == 1);
        
        return $option;
    }

    protected function argument($name, $isRequired, $isArray)
    {
        $argument = $this->quickMock('\PHPixie\Console\Command\Config\Argument');
        
        $this->method($argument, 'name', $name);
        $this->method($argument, 'isRequired', $isRequired);
        $this->method($argument, 'isArray', $isArray);
        
        return $argument;
    }
}
