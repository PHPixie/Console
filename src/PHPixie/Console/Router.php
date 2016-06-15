<?php 

class Router
{
    protected $registry;
    
    public function match($name)
    {
        $command = $registry->command($name);
        if($command !== null) {
            return $command;
        }
        
        $nestedRegistry = $registry->command
    }
}
