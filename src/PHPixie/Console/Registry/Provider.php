<?php 

namespace PHPixie\Console\Registry;

interface Provider
{
    public function buildCommand($name, $config);
    public function commandNames();
}
