<?php

namespace PHPixie\Console\Context;

class SAPI implements \PHPixie\Console\Context
{
    protected $builder;

    protected $inputStream;
    protected $outputStream;
    protected $errorStream;

    protected $currentDirectory;

    protected $options;
    protected $arguments;

    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    public function inputStream()
    {
        if($this->inputStream === null) {
            $this->inputStream = $this->builder->inputStream(STDIN);
        }

        return $this->inputStream;
    }

    public function outputStream()
    {
        if($this->outputStream === null) {
            $this->outputStream = $this->builder->outputStream(STDOUT);
        }

        return $this->outputStream;
    }

    public function errorStream()
    {
        if($this->errorStream === null) {
            $this->errorStream = $this->builder->outputStream(STDERR);
        }

        return $this->errorStream;
    }

    public function currentDirectory()
    {
        if($this->currentDirectory === null) {
            $this->currentDirectory = getcwd();
        }

        return $this->currentDirectory;
    }

    public function arguments()
    {
        $this->requireParsedArguments();
        return $this->arguments;
    }

    public function options()
    {
        $this->requireParsedArguments();
        return $this->options;
    }

    public function rawArguments()
    {
        global $argv;
        return $argv;
    }

    public function requireParsedArguments()
    {
        if($this->arguments !== null) {
            return;
        }

        global $argv;
        $parameters = $this->rawArguments();

        $arguments = array();
        $options = array();

        array_shift($parameters);
        foreach($parameters as $parameter) {

            if($parameter{0} !== '-') {
                $arguments[]= $parameter;
                continue;
            }

            if(preg_match('#^-([a-z0-9])=(.*)$#i', $parameter, $matches)) {
                foreach(str_split($matches[1]) as $option) {
                    $options[$matches[1]] = $matches[2];
                }
                continue;
            }

            if(preg_match('#^-([a-z0-9]*)$#i', $parameter, $matches)) {
                foreach(str_split($matches[1]) as $option) {
                    $options[$option] = true;
                }
                continue;
            }

            if(preg_match('#^--([a-z0-9]+)(?:=(.*))?$#i', $parameter, $matches)) {
                $option = $matches[1];

                if(!empty($matches[2])) {
                    $options[$option] = $matches[2];
                }else{
                    $options[$option] = true;
                }
                continue;
            }

            throw new \PHPixie\Console\Exception("Invalid option '$parameter'");
        }

        $this->options = $this->builder->data($options);
        $this->arguments = $this->builder->data($arguments);
    }
}
