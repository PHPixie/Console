<?php

namespace PHPixie\Command;

class Runner
{
    public function run($command)
    {
        $data = $context->options()->get();

        $options = $command->getOptions();

        $names = array();
        $required = array();

        foreach ($options as $option) {
            $names[] = $option->name();
            if($option->isRequired()) {
                $required[] = $option->name();
            }
        }

        $keys = array_keys($data);
        $extraKeys = array_intersect($keys, $names);
        if(!empty($extraKeys)) {
            throw new \PHPixie\Console\Exception("Invalid option(s): ".implode(', ', $extraKeys)));
        }

        $missingKeys = array_intersect($required, $keys);
        if(!empty($missingKeys)) {
            throw new \PHPixie\Console\Exception("Missing required option(s): ".implode(', ', $missingKeys)));
        }

        $command->process();
    }
}
