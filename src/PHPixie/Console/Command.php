<?php

namespace PHPixie\Console;

interface Command
{
    public function run($optionData, $argumentData);
    public function config();
    public function name();
}
