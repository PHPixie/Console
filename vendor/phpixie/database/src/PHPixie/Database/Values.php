<?php

namespace PHPixie\Database;

class Values
{
    public function orderBy($field, $direction)
    {
        return new \PHPixie\Database\Values\OrderBy($field, $direction);
    }
}