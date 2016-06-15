<?php 

interface Registry
{
    public function command($name);
    public function allCommands();
}
