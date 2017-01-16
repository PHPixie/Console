<?php

namespace PHPixie\Console;

use PHPixie\Console\Exception\InvalidInputException;
use PHPixie\Console\Exception\CommandException;

class Runner
{
    protected $builder;
    
    public function __construct($builder)
    {
        $this->builder = $builder;
    }
    
    public function runFromContext()
    {
        $cliContext = $this->builder->cli()->context();
        $arguments = $cliContext->arguments();
        
        $commandName = $this->builder->registry()->defaultName();
        
        if(!empty($arguments)) {
            $commandName = array_shift($arguments);
        }
        
        $this->runCommand($commandName, $arguments, $cliContext->options(), true);
    }
    
    public function runCommand($commandName, $arguments, $options, $rethrowException = true)
    {
        $cliContext = $this->builder->cli()->context();
        $command = $this->builder->registry()->get($commandName);
        $config = $command->config();

        try{
            $optionData = $this->getOptions($config->getOptions(), $options);
            $argumentData = $this->getArguments($config->getArguments(), $arguments);
            
            $message = $command->run($argumentData, $optionData);
            if($message !== null) {
                $cliContext->outputStream()->writeLine($message);
            }
        } catch(\Throwable $e) {
            $this->handleException($config, $cliContext, $rethrowException, $e);
        } catch(\Exception $e) {
            $this->handleException($config, $cliContext, $rethrowException, $e);
        }
    }
    
    protected function handleException($config, $cliContext, $rethrowException, $e)
    {
        $cliContext->setExitCode(1);
        $errorStream = $cliContext->errorStream();

        if(!($e instanceof CommandException)) {
            if($rethrowException) {
                throw $e;
            }

            $errorStream->writeLine($this->builder->formatter()->exception($e));
            return;
        }

        $errorStream->writeLine($this->builder->formatter()->error($e->getMessage()));

        if($e instanceof InvalidInputException) {
            $errorStream->writeLine($config->getUsage());
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
            throw new InvalidInputException("Invalid option(s): ".implode(', ', $extraKeys));
        }

        $missingKeys = array_intersect($required, $keys);
        if(!empty($missingKeys)) {
            throw new InvalidInputException("Missing required option(s): ".implode(', ', $missingKeys));
        }
        
        return $this->builder->arraySlice($optionData);
    }
    
    protected function getArguments($arguments, $argumentData)
    {
        $map = array();
        
        $mappedAll = false;
        
        $i = 0;
        foreach($arguments as $key => $argument) {
            if(!array_key_exists($i, $argumentData)) {
                if($argument->isRequired()) {
                    throw new InvalidInputException("Missing argument: {$argument->name()}");
                }
                
                break;
            }
            
            if($argument->isArray()) {
                $map[$argument->name()] = array_slice($argumentData, $i);
                $mappedAll = true;
                break;
            }
            
            $map[$argument->name()] = $argumentData[$i];
            $i++;
        }
        
        if(!$mappedAll && count($argumentData) > count($arguments)) {
            throw new Exception\InvalidInputException("Too many arguments supplied");
        }
        
        return $this->builder->arraySlice($map);
    }
}
