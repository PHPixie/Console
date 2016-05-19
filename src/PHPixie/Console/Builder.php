<?php

class Builder
{
    public function input($resource)
    {
        return new Streams\Input($resource);
    }

    public function output($resource)
    {
        return new Streams\Output($resource);
    }
}
