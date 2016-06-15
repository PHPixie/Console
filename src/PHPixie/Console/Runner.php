<?php

namespace PHPixie\Command;

class Runner
{
    protected $cliContextContainer;
    
    public function __construct($cliContextContainer)
    {
        $this->$cliContextContainer = $cliContextContainer;
    }
    
    public function runFromContext()
    {
        $cliContext = $this->cliContextContainer->cliContext();
        $arguments = $cliContext->arguments();
        
        $commandName = 'help';
        if(!empty($arguments)) {
            $commandName = array_shift($arguments);
        }
        
        $this->runCommand($commandName, $arguments, $cliContext->options(), false);
    }
    
    public function runCommand($commandName, $arguments, $options, $rethrowException = true)
    {
        $cliContext = $this->cliContextContainer->cliContext();
        
        try{
            $command = $this->registry->command($name);
            
            $optionData = $this->getOptions($config->options(), $options);
            $argumentData = $this->getArguments($config->arguments(), $arguments);
            
            $message = $command->run($cliContext, $argumentData, $optionData);
            if($message !== null) {
                $cliContext->outputStream()->writeln($message);
            }
        } catch(\Exception $e) {
            $cliContext->setExitCode(1);
            $errorStream = $cliContext->errorStream();
            
            if(!($e instanceof CommandException)) {
                if($rethrowException) {
                    throw $e;
                }
                
                $errorStream->writeln($this->formatter()->exception($e));
                return;
            }
            
            $errorStream->writeln($this->formatter()->error($e->getError()));
            
            if($e instanceof InvalidInputException) {
                $errorStream->writeln($command->usage());
            }
        }
    }
    
    protected function getOptions($options, $optionData)
    {
        $names = array();
        $required = array();

        foreach ($options as $option) {
            $names[] = $option->name();
            if($option->isRequired()) {
                $required[] = $option->name();
            }
        }

        $keys = array_keys($optionData);
        
        $extraKeys = array_diff($keys, $names);
        if(!empty($extraKeys)) {
            throw new InvalidInputException("Invalid option(s): ".implode(', ', $extraKeys)));
        }

        $missingKeys = array_intersect($required, $keys);
        if(!empty($missingKeys)) {
            throw new InvalidInputException("Missing required option(s): ".implode(', ', $missingKeys)));
        }
        
        return $this->builder->arraySlice($optionsData);
    }
    
    protected function getArguments($arguments, $argumentData)
    {
        $map = array();
        
        $mappedAll = false;
        foreach($arguments as $key => $argument) {
            if(!array_key_exists($key, $rawArguments)) {
                if($argument->isRequired()) {
                    throw new InvalidInputException("Missing argument: {$argument->name()}");
                }
                
                break;
            }
            
            if($argument->isArray()) {
                $map[$argument->name()] = array_slice($argumentData, $key);
                $mappedAll = true;
                break;
            }
            
            $map[$argument->name()] = $argumentData[$key];
        }
        
        if(!$mappedAll && count($arguments) > count($argumentData)) {
            throw new InvalidInputException("Too many arguments supplied");
        }
        
        return $this->builder->arraySlice($map);
    }
}
