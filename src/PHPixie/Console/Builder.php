<?php

namespace PHPixie\Console;

class Builder
{
    protected $slice;
    protected $contextContainer;

    public function __construct($slice, $contextContainer = null)
    {
        $this->slice = $slice;
        $this->contextContainer = $contextContainer;
    }

    public function inputStream($resource)
    {
        return new Streams\Input($resource);
    }

    public function outputStream($resource)
    {
        return new Streams\Output($resource);
    }

    public function data($data)
    {
        return $this->slice->arrayData($data);
    }

    public function context()
    {
        return $this->contextContainer()->consoleContext();
    }

    public function contextContainer()
    {
        if($this->contextContainer === null) {
            $context = $this->buildSapiContext();
            $this->contextContainer = $this->buildContextContainer($context);
        }

        return $this->contextContainer;
    }

    public function buildContextContainer($context)
    {
        return new \PHPixie\Console\Context\Container\Implementation($context);
    }

    public function buildSapiContext()
    {
        return new Context\SAPI($this);
    }
}
