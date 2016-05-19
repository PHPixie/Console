<?php

namespace PHPixie\Console;

interface Context
{
    public function inputStream();
    public function outputStream();
    public function errorStream();
    public function currentDirectory();
    public function arguments();
}
