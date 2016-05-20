<?php

namespace PHPixie\Console\Stream;

class Input
{
    protected $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function readUntilEnd()
    {
        return stream_get_contents($this->resource, -1);
    }

    public function finished()
    {
        return feof($this->resource);
    }

    /**
     * @inheritdoc
     */
    public function read($length)
    {
        return fread($this->resource, $length);
    }

    /**
     * @inheritdoc
     */
    public function readLine()
    {
        return fgets($this->resource, $length);
    }
}
