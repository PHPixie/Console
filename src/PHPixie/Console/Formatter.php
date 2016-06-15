<?php

namespace PHPixie\Command;

class Formatter
{
    public function usage($name, $options, $arguments)
    {
        $str = $name;

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

}
