<?php

namespace Aoc2019\Day24;

use PHPUnit\Framework\TestCase;

class Day24Test extends TestCase
{
    /** @test */
    public function biodiversityTrackerFindsFirstRepeatingVariation()
    {
        $biodiversityTracker = new BiodiversityTracker();

        $biodiversityTracker->loadMap(file_get_contents(__DIR__ . '/input-test.txt'));

        $this->assertEquals(2129920, $biodiversityTracker->findFirstRepeatingVariation());
    }

    /** @test */
    public function biodiversityTrackerCountsBugsAfterNumberOfEvolutions()
    {
        $biodiversityTracker = new BiodiversityTracker();

        $biodiversityTracker->loadMap(file_get_contents(__DIR__ . '/input-test.txt'));

        $this->assertEquals(99, $biodiversityTracker->countBugsAfterEvolution(10));
    }
}
