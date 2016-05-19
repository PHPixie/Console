<?php

namespace PHPixie\Console;

class Input
{
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

    public function getCurrentDirectory()
    {
        return getcwd();
    }

    public function getArguments()
    {
        global $argv;
        $args = $argv;
        array_shift($args);
        return $args;
    }
}
