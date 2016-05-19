<?php

namespace PHPixie\Console\Context;

class SAPI extends \PHPixie\Console\Context
{
    protected $streams;

    protected $inputStream;
    protected $outputStream;
    protected $errorStream;
    protected $currentDirectory;
    protected $arguments;

    public function __construct($streams)
    {
        $this->streams = $streams;
    }

    public function inputStream()
    {
        if($this->inputStream === null) {
            $this->inputStream = $this->streams->input(STDIN);
        }

        return $this->inputStream;
    }

    public function outputStream()
    {
        if($this->outputStream === null) {
            $this->outputStream = $this->streams->output(STDOUT);
        }

        return $this->outputStream;
    }

    public function errorStream()
    {
        if($this->errorStream === null) {
            $this->errorStream = $this->streams->output(STDERR);
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
        if($this->arguments === null) {
            global $argv;
            $args = $argv;
            array_shift($args);
            return $args;
        }

        return $this->arguments;
    }

    public function getOptions(
        $long = array(),
        $short = array(),
        $flags = array(),
        $shortAliases = array()
    )
    {
        $longOpts = array();
        $shortOpts = array();

        $flags = array_fill_keys($flags, true);

        foreach($long as $name) {
            $opt = isset($flags[$name]) ? $name : $name.'::';
            $longOpts[] = $opt;
        }

        foreach($short as $name) {
            $opt = isset($flags[$name]) ? $name : $name.'::';
            $shortOpts[$opt] = true;
        }

        foreach($shortAliases as $alias => $name) {
            $opt = isset($flags[$name]) ? $alias : $alias.'::';
            $shortOpts[$opt] = true;
        }

        $shortOpts = implode('', array_keys($shortOpts));
        $data = getopt($shortOpts, $longOpts);

        $result = array();
        foreach($data as $name => $value) {
            if(isset($shortAliases[$name])) {
                $name = $shortAliases[$name];
            }

            if(isset($flags[$name])) {
                $value = true;
            }

            $result[$name] = $value;
        }

        return $result;
}
