<?php

namespace Aoc2019\Day20;

use PHPUnit\Framework\TestCase;

class Day20Test extends TestCase
{
    /** @test */
    public function pathSeekerFindsExitOnSingleMap()
    {
        $pathSeeker = new PathSeeker;

        $pathSeeker->loadMap(file_get_contents(__DIR__ . '/input-test-single.txt'));

        $this->assertEquals(58, $pathSeeker->findMinPathOnSingleMap());
    }

    /** @test */
    public function pathSeekerFindsExitOnRecursiveMap()
    {
        $pathSeeker = new PathSeeker;

        $pathSeeker->loadMap(file_get_contents(__DIR__ . '/input-test-recursive.txt'));

        $this->assertEquals(396, $pathSeeker->findMinPathOnRecursiveMap());
    }
}
