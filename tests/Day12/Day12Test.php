<?php

namespace Aoc2019\Day12;

use PHPUnit\Framework\TestCase;

class Day12Test extends TestCase
{
    /** @test */
    public function gravitySimulatorMovesObjects()
    {
        $simulator = new GravitySimulator;
        $simulator->addObject(new GravityObject([-1, 0, 2]));
        $simulator->addObject(new GravityObject([2, -10, -7]));
        $simulator->addObject(new GravityObject([4, -8, 8]));
        $simulator->addObject(new GravityObject([3, 5, -1]));

        for ($i = 0; $i < 10; $i++) {
            $simulator->recalculateVelocities();
            $simulator->recalculatePositions();
        }

        $this->assertEquals([2, 1, -3], $simulator->getObject(0)->getPositionArray());
        $this->assertEquals([-3, -2, 1], $simulator->getObject(0)->getVelocityArray());
        $this->assertEquals([1, -8, 0], $simulator->getObject(1)->getPositionArray());
        $this->assertEquals([-1, 1, 3], $simulator->getObject(1)->getVelocityArray());
        $this->assertEquals([3, -6, 1], $simulator->getObject(2)->getPositionArray());
        $this->assertEquals([3, 2, -3], $simulator->getObject(2)->getVelocityArray());
        $this->assertEquals([2, 0, 4], $simulator->getObject(3)->getPositionArray());
        $this->assertEquals([1, -1, -1], $simulator->getObject(3)->getVelocityArray());
    }

    /** @test */
    public function gravitySimulatorCalculatesEnergy()
    {
        $simulator = new GravitySimulator;
        $simulator->addObject(new GravityObject([-1, 0, 2]));
        $simulator->addObject(new GravityObject([2, -10, -7]));
        $simulator->addObject(new GravityObject([4, -8, 8]));
        $simulator->addObject(new GravityObject([3, 5, -1]));

        for ($i = 0; $i < 10; $i++) {
            $simulator->recalculateVelocities();
            $simulator->recalculatePositions();
        }

        $this->assertEquals(179, $simulator->calculateTotalEnergy());
    }
}
