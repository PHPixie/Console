<?php 

namespace PHPixie\Console\Command;

class Config
{
    /**
     *
     * @var \PHPixie\Console\Builder 
     */
    protected $builder;
    protected $prefix;
    protected $name;
    protected $description;
    protected $usage;
    protected $options = array();
    protected $arguments = array();
    protected $help;
    
    /**
     * 
     * @param \PHPixie\Console\Builder $builder
     * @param string $name
     */
    public function __construct($builder, $name)
    {
        $this->builder = $builder;
        $this->name  = $name;
    }
    
    public function description($description)
    {
        $this->description = $description;
        return $this;
    }
    
    public function usage($usage)
    {
        $this->usage = $usage;
        return $this;
    }
    
    public function help($help)
    {
        $this->help = $help;
        return $this;
    }
    
    /**
     * 
     * @param string $name
     * @return Config\Option
     * @throws Exception
     */
    public function option($name)
    {
        if(isset($this->options[$name])) {
            throw new Exception("Option $name is already defined");
        }
        
        $option = $this->buildOption($name);
        $this->options[$name] = $option;
        return $option;
    }
    
    /**
     * 
     * @param type $name
     * @return Config\Argument
     * @throws Exception
     */
    public function argument($name)
    {
        if(isset($this->arguments[$name])) {
            throw new Exception("Argument $name is already defined");
        }
        
        $argument = $this->buildArgument($name);
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
            $this->usage = $this->builder->formatter()->usage($this);
        }
        
        return $this->usage;
    }
    
    public function getHelp()
    {
        if($this->help !== null) {
            return $this->help;
        }
        
        return $this->builder->formatter()->fullUsage($this);
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
            
            if($argument->isArray()) {
                $arrayArgument = $name;
            }
        }
    }
    
    /**
     * 
     * @return \PHPixie\CLI\Context\SAPI
     */
    public function cliContext()
    {
        return $this->builder->cli()->context();
    }
    
    /**
     * 
     * @param string $name
     * @return \PHPixie\Console\Command\Config\Option
     */
    protected function buildOption($name)
    {
        return new Config\Option($name);
    }
    
    /**
     * 
     * @param string $name
     * @return \PHPixie\Console\Command\Config\Argument
     */
    protected function buildArgument($name)
    {
        return new Config\Argument($name);
    }
}
