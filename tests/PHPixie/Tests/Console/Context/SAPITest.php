<?php

namespace PHPixie\Console\Context;

class SAPITest extends \PHPixie\Test\Testcase
{
    protected $builder;
    protected $context;

    public function setUp()
    {
        $this->builder = $this->quickMock('PHPixie\Console\Builder');
        $this->context = new \PHPixie\Console\Context\SAPI($this->builder);
    }

    public function testInputStream()
    {
        $this->streamTest('inputStream', 'input', STDIN);
    }

    public function testOutputStream()
    {
        $this->streamTest('outputStream', 'output', STDOUT);
    }

    public function testErrorStream()
    {
        $this->streamTest('errorStream', 'output', STDERR);
    }

    public function testCurrentDirectory()
    {
        for($i=0; $i<2; $i++) {
            $this->assertSame(getcwd(), $this->context->currentDirectory());
        }
    }

    public function testRawArguments()
    {
        global $argv;
        for($i=0; $i<2; $i++) {
            $this->assertSame($argv, $this->context->rawArguments());
        }
    }

    public function testArguments()
    {
        $context = $this->contextMock(array('rawArguments'));

        $this->method($context, 'rawArguments', array(
            '',
            'test',
            '-abc',
            '-d=4',
            'test2',
            '--debug',
            '--value=5'
        ), array(), 0);

        $options = $this->quickMock('\PHPixie\Slice\Data');
        $arguments = $this->quickMock('\PHPixie\Slice\Data');

        $this->method($this->builder, 'data', $options, array(array(
            'a' => true,
            'b' => true,
            'c' => true,
            'd' => '4',
            'debug' => true,
            'value' => '5'
        )), 0);

        $this->method($this->builder, 'data', $arguments, array(array(
            'test',
            'test2'
        )), 1);

        $this->assertSame($options, $context->options());
        $this->assertSame($arguments, $context->arguments());


        $context = $this->contextMock(array('rawArguments'));

        $this->method($context, 'rawArguments', array(
            '',
            '-c-d'
        ), array(), 0);

        $this->assertException(function() use($context) {
            $context->options();
        }, '\PHPixie\Console\Exception');
    }

    protected function streamTest($method, $type, $resource)
    {
        $stream = $this->quickMock('PHPixie\Console\Streams\\'.ucfirst($type));
        $this->method($this->builder, $type.'Stream', $stream, array($resource), 0);

        for($i=0; $i<2; $i++) {
            $this->assertSame($stream, call_user_func(array($this->context, $method)));
        }
    }

    protected function contextMock($methods)
    {
        return $this->getMock(
            '\PHPixie\Console\Context\SAPI',
            $methods,
            array($this->builder)
        );
    }
}
