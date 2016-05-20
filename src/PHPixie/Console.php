<?php

namespace PHPixie;

class Console
{
    /**
     * @type Console\Builder
     */
    protected $builder;

    public function __construct($slice)
    {
        $this->builder = $this->buildBuilder($slice);
    }

    public function context()
    {
        return $this->builder->context();
    }

    protected function buildBuilder($slice)
    {
        return new Console\Builder($slice);
    }
}
