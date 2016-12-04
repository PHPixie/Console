<?php

namespace PHPixie\Console;

class Builder
{
    /**
     *
     * @var \PHPixie\Slice
     */
    protected $slice;
    
    /**
     *
     * @var \PHPixie\CLI 
     */
    protected $cli;
    
    protected $registry;
    protected $formatter;
    /**
     *
     * @var \PHPixie\Console\Registry\Provider
     */
    protected $provider;
    protected $runner;

    /**
     * 
     * @param \PHPixie\Slice $slice
     * @param \PHPixie\CLI $cli
     * @param \PHPixie\Console\Registry\Provider $provider
     */
    public function __construct($slice, $cli, $provider)
    {
        $this->slice = $slice;
        $this->cli = $cli;
        $this->provider = $provider;
    }
    
    public function runner()
    {
        if($this->runner === null) {
            $this->runner = new Runner($this);
        }
        
        return $this->runner;
    }
    
    public function cli()
    {
        return $this->cli;
    }
    
    public function registry()
    {
        if($this->registry === null) {
            $this->registry = new Registry(
                $this,
                $this->provider
            );
        }
        
        return $this->registry;
    }
    
    public function formatter()
    {
        if($this->formatter === null) {
            $this->formatter = new Formatter();
        }
        
        return $this->formatter;
    }
    
    public function buildConfigBuilder()
    {
        return new Registry\ConfigBuilder($this);
    }
    
    public function buildCommandConfig($name)
    {
        return new Command\Config($this, $name);
    }
    
    public function arraySlice($data)
    {
        return $this->slice->arraySlice($data);
    }
}
