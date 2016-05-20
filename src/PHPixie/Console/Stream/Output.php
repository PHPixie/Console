<?php

class Output
{
    protected $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function write($string)
    {
        fwrite($this->resource, $string)
    }

    public function writeLine($string)
    {
        $this->write($string."\n");
    }
}
