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

    public function charPrompt($string)
    {
        $context->outputStream()->write($string.' ');
        return $context->inputStream()->read(1);
    }

    public function table($rows)
    {
        $sizes = array();
        foreach($rows as $i => $row) {
            $rows[$i] = (array) $row;
        }

        $keys = array_keys($rows[0]);

        foreach($keys as $key) {
            $sizes[$key] = strlen($key);
        }

        foreach($rows as $row) {
            foreach($keys as $key) {
                $length = strlen($row[$key]);
                if($sizes[$key] < $length) {
                    $sizes[$key] = $length;
                }
            }
        }

        foreach($sizes as $key => $size) {
            $sizes[$key]+= 4;
        }

        $separator = '';
        foreach($sizes as $size) {
            $separator. = str_pad('+', '-', $size);
        }
        $separator.="+\n";

        $result = $separator;

        array_unshift(array_combine($keys, $keys), $rows);
        foreach($rows as $i => $row) {

        }


    }

    protected function tableSeparator($sizes)
    {

    }

    protected function context()
    {
        return $this->contextContainer->consoleContext();
    }
}
