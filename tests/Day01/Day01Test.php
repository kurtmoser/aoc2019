<?php

namespace Aoc2019\Day01;

use PHPUnit\Framework\TestCase;

class Day01Test extends TestCase
{
    /** @test */
    public function fuelRequirementIsCalculatedCorrectly()
    {
        $this->assertEquals(2, (new Day01Solver)->calculateFuel([12]));
        $this->assertEquals(2, (new Day01Solver)->calculateFuel([14]));
        $this->assertEquals(654, (new Day01Solver)->calculateFuel([1969]));
        $this->assertEquals(33583, (new Day01Solver)->calculateFuel([100756]));
    }

    /** @test */
    public function recursiveFuelRequirementIsCalculatedCorrectly()
    {
        $this->assertEquals(2, (new Day01Solver)->calculateRecursiveFuel([14]));
        $this->assertEquals(966, (new Day01Solver)->calculateRecursiveFuel([1969]));
        $this->assertEquals(50346, (new Day01Solver)->calculateRecursiveFuel([100756]));
    }
}
