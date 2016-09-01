<?php 

namespace PHPixie\Console\Registry\Command;

class Help extends \PHPixie\Console\Command\Implementation
{
    protected $builder;
    
    public function __construct($config, $builder)
    {
        $this->builder = $builder;
        
        $config
            ->description('Print command list and usage');
        
        $config->argument('commandName')
            ->description("Name of the command to print the usage of");
        
        parent::__construct($config);
    }
    
    public function run($argumentData, $optionData)
    {
        $registry = $this->builder->registry();
        
        if($commandName = $argumentData->get('commandName')) {
            $command = $registry->get($commandName);
            if($command === null) {
                throw new CommandException("Command '$commandName' not found.");
            }
            
            return $command->config()->getHelp();
        }
        
        $commands = $registry->all();
        
        ksort($commands);
        
        $str = "Available commands:\n\n";
        
        $len = 0;
        foreach($commands as $command) {
            $len = max($len, strlen($command->name()));
        }
        
        $len+= 4;
        
        foreach($commands as $command) {
            $str.= str_pad($command->name(), $len);
            $str.= $command->config()->getDescription();
            $str.="\n";
        }
        
        return $str;
    }
}
