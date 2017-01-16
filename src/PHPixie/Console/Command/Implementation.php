<?php

namespace PHPixie\Console\Command;

abstract class Implementation implements \PHPixie\Console\Command
{
    /**
     *
     * @var \PHPixie\Console\Command\Config 
     */
    protected $config;
    
    /**
     * 
     * @param \PHPixie\Console\Command\Config $config
     */
    public function __construct($config)
    {
        $config->assertValid();
        $this->config = $config;
    }
    
    /**
     * 
     * @return \PHPixie\Console\Command\Config
     */
    public function config()
    {
        return $this->config;
    }
    
    public function name()
    {
        return $this->config()->getName();
    }
    
    protected function cliContext()
    {
        return $this->config->cliContext();
    }
    
    protected function write($string)
    {
        $this->cliContext()->outputStream()->write($string);
    }
    
    protected function writeLine($string = '')
    {
        $this->cliContext()->outputStream()->writeLine($string);
    }
    
    protected function readLine()
    {
        return $this->cliContext->inputStream()->readLine();
    }
    
    abstract public function run($optionData, $argumentData);
}
