<?php

namespace PHPixie\Console;

class Prompt
{
    protected $contextContainer;

    public function __construct($contextContainer)
    {
        $this->contextContainer = $contextContainer;
    }

    public function prompt($string, $newLine = false)
    {
        $context = $this->context();
        $string.= $newLine ? "\n" : ' ';

        $context->outputStream()->write($string);
        return $context->inputStream()->readLine();
    }

    public function charPrompt($string)
    {
        $context->outputStream()->write($string.' ');
        return $context->inputStream()->read(1);
    }

    protected function context()
    {
        return $this->contextContainer->consoleContext();
    }
}
