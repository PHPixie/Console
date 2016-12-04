<?php 

namespace PHPixie\Console;

class Registry
{
    protected $builder;
    protected $provider;
    
    protected $commands = [];
    protected $hasAll = false;
    
    public function __construct($builder, $provider)
    {
        $this->builder = $builder;
        $this->provider = $provider;
    }
    
    public function get($name)
    {
        if(!isset($this->commands[$name])) {
            if(!in_array($name, $this->commandNames())) {
                throw new \PHPixie\Console\Exception\CommandException("Command '$name' does not exist");
            }
            $this->commands[$name] = $this->buildCommand($name);
        }
        
        return $this->commands[$name];
    }
    
    public function defaultName()
    {
        return 'help';
    }
    
    public function all()
    {
        if($this->hasAll) {
            return $this->commands;
        }
        
        foreach($this->commandNames() as $name) {
            $this->get($name);
        }
        
        $this->hasAll = true;
        return $this->commands;
    }
    
    protected function buildCommand($name)
    {
        $config = $this->builder->buildCommandConfig($name);
        
        if($name === 'help') {
            return new Registry\Command\Help($config, $this->builder);
        }
        
        return $this->provider->buildCommand($name, $config);
    }
    
    protected function commandNames()
    {
        $commandNames = $this->provider->commandNames();
        array_unshift($commandNames, 'help');
        return $commandNames;
    }
}
