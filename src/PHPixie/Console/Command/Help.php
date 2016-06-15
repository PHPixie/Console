<?php 

namespace PHPixie\Console\Command;

class Help extends \PHPixie\Console\Command\Command
{
    protected $builder;
    
    public function __construct($config, $builder)
    {
        $this->builder = $builder;
        
        $config
            ->name('help')
            ->description('Print command list and usage');
        
        $config->argument('commandName')
            ->description("Name of the command to print the usage of");
        
        parent::__construct($config);
    }
    
    public function run($cliContext, $argumentData, $optionData)
    {
        $registry = $this->builder->registry();
        
        if($commandName = $argumentData->get('commandName')) {
            $command = $registry->command($commandName);
            if($command === null) {
                throw new CommandException("Command '$commandName' not found.")
            }
            
            return $command->config()->help();
        }
        
        $allCommands = $registry->allCommands();
        return $this->builder->formatter->commandList($allCommands);
    }
}
