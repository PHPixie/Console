<?php 

namespace PHPixie\Console\Command;

class Config
{
    protected $builder;
    protected $prefix;
    
    public function __construct($builder, $prefix)
    {
        $this->builder = $builder;
        $this->prefix  = $prefix;
    }
    
    public function name($name)
    {
        $this->name = $this->prefix.$name;
    }
    
    public function description($description)
    {
        $this->description = $description;
    }
    
    public function usage($usage)
    {
        $this->usage = $usage;
    }
    
    public function help($help)
    {
        $this->help = $help;
    }
    
    public function option($name)
    {
        if(isset($this->options[$name])) {
            throw new Exception("Option $name is already defined");
        }
        
        $option = $this->builder->option($name);
        $this->options[$name] = $option;
        return $option;
    }
    
    public function argument($name)
    {
        if(isset($this->arguments[$name])) {
            throw new Exception("Argument $name is already defined");
        }
        
        $argument = $this->builder->argument($name);
        $this->arguments[$name] = $argument;
        return $argument;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function getArguments()
    {
        return $this->arguments;
    }
    
    public function getUsage()
    {
        if($this->usage !== null) {
            $this->usage = $this->builder->formatter()->usage(
                $this->name,
                $this->options,
                $this->arguments
            );
        }
        
        return $this->usage;
    }
    
    public function geHelp()
    {
        if($this->help !== null) {
            $this->help = $this->builder->formatter()->fullUsage(
                $this->name,
                $this->options,
                $this->arguments
            );
        }
        
        return $this->help;
    }
    
    public function assertValid()
    {
        if($this->name === null) {
            throw new Exception("Command name not defined");
        }
        
        $lastOptional = null;
        $arrayArgument = null;
        
        foreach($this->arguments as $argument) {
            $name = $argument->name();
            
            if($arrayArgument !== null) {
                throw new Exception("Argument $name cannot follow an array argument $arrayArgument");
            }
            
            if($argument->isRequired() && $lastOptional !== null) {
                throw new Exception("Required argument $name cannot follow optional argument $lastOptional");
            }
            
            if(!$argument->isRequired()) {
                $lastOptional = $name;
            }
            
            if($argument->isRequired()) {
                $arrayArgument = $name;
            }
        }
    }
}
