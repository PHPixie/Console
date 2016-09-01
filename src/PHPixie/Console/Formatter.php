<?php

namespace PHPixie\Console;

class Formatter
{
    public function usage($config)
    {
        $options = $config->getOptions();
        $arguments = $config->getArguments();
        
        $options = $this->sortOptions($options);
        
        $str = $config->getName();
        
        foreach($options as $option) {
            $optionStr = $option->isShort() ? '-' : '--';
            $optionStr.= $option->name();

            if(!$option->isFlag()) {
                $optionStr.='=VALUE';
            }

            if(!$option->isRequired()) {
                $optionStr = "[ $optionStr ]";
            }

            $str.= ' '.$optionStr;
        }

        foreach($arguments as $argument)
        {
            $argumentStr = strtoupper($argument->name());
            if($argument->isArray()) {
                $argumentStr.='...';
            }
            if(!$argument->isRequired()) {
                $argumentStr = "[ $argumentStr ]";
            }

            $str.= ' '.$argumentStr;
        }

        return $str;
    }
    
    public function fullUsage($config)
    {
        $str = $this->usage($config)."\n";
        $str.= $config->getDescription()."\n";
        $options = $config->getOptions();
        $arguments = $config->getArguments();
        
        $options = $this->sortOptions($options);
        
        if(!empty($options)) {
            $str.="\nOptions:\n";
            $pad = $this->getPad($options);
            foreach($options as $option) {
                $str.=str_pad($option->name(), $pad).$option->getDescription()."\n";
            }
        }
        
        if(!empty($arguments)) {
            $str.="\nArguments:\n";
            $pad = $this->getPad($arguments);
        
            foreach($arguments as $argument) {
                $str.= str_pad(strtoupper($argument->name()), $pad);
                $str.= $argument->getDescription()."\n";
            }
        }
        
        
        return $str;
    }
    
    protected function sortOptions($options)
    {
        usort($options, function($a, $b) {
            if($a->isRequired() !== $b->isRequired()) {
                return !$a->isRequired();
            }

            if($a->isShort() !== $b->isShort()) {
                return !$a->isShort();
            }

            if($a->isFlag() !== $b->isFlag()) {
                return !$a->isFlag();
            }

            return $a->name() > $b->name();
        });
        
        return $options;
    }
    
    protected function getPad($items)
    {
        $len = 0;
        foreach($items as $item) {
            $len = max(0, strlen($item->name()));
        }
        
        return $len+4;
    }
    
    public function exception($exception)
    {
        return $exception->getMessage();
    }
    
    public function error($error)
    {
        return $error;
    }
}
