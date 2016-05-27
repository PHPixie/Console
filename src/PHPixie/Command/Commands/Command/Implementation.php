<?php

namespace PHPixie\Command\Commands;

class Command
{
    protected $description;

    public function description()
    {
        return $this->description;
    }

    public function process()
    {
        $optionData = $context->options()->get();

        $options = $command->getOptions();

        $names = array();
        $required = array();

        foreach ($options as $option) {
            $names[] = $option->name();
            if($option->isRequired()) {
                $required[] = $option->name();
            }
        }

        $keys = array_keys($optionData);
        $extraKeys = array_intersect($keys, $names);
        if(!empty($extraKeys)) {
            throw new \PHPixie\Console\Exception("Invalid option(s): ".implode(', ', $extraKeys)));
        }

        $missingKeys = array_intersect($required, $keys);
        if(!empty($missingKeys)) {
            throw new \PHPixie\Console\Exception("Missing required option(s): ".implode(', ', $missingKeys)));
        }

        $argumentCount = count($context->arguments()->get());
        if($argumentCount < $command->requiredArgumentCount()) {
            throw new \PHPixie\Console\Exception("Not enough arguments supplied");
        }

        if($argumentCount > $command->requiredArgumentCount() && !$command->allowExtraArguments()) {
            throw new \PHPixie\Console\Exception("Too many arguments supplied");
        }

        $command->process();
    }

    protected function printUsage()
    {
        $str = $this->getFullName();

        $options = $this->getOptions();

        usort($options, function($a, $b)) {
            if($a->isRequired() !== $b->isRequired()) {
                return $a->isRequired();
            }

            if($a->isShort() !== $b->isShort()) {
                return $a->isShort();
            }

            return $a->name() > $b->name();
        }

        foreach($options as $option) {
            $optionStr = $option->isShort() ? '-' : '--';
            $optionStr.= $option->name;

            if(!$option->isFlag()) {
                $optionStr.='=VALUE';
            }

            if(!$option->isRequired) {
                $optionStr = "[ $optionStr ]";
            }

            $str.= ' '.$optionStr.' ';
        }
    }
}
