<?php

namespace Aoc2019\Day20;

class Point
{
    public $x;
    public $y;
    public $z;

    public function __construct($x, $y, $z = 0)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }
}
