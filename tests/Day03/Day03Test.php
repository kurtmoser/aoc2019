<?php

namespace Aoc2019\Day03;

use PHPUnit\Framework\TestCase;

class Day03Test extends TestCase
{
    /** @test */
    public function wireSolverCalculatesMinDistanceCrossing()
    {
        $this->assertEquals(6, WireSolver::getMinDistanceCrossing(
            'R8,U5,L5,D3',
            'U7,R6,D4,L4'
        ));

        $this->assertEquals(159, WireSolver::getMinDistanceCrossing(
            'R75,D30,R83,U83,L12,D49,R71,U7,L72',
            'U62,R66,U55,R34,D71,R55,D58,R83'
        ));

        $this->assertEquals(135, WireSolver::getMinDistanceCrossing(
            'R98,U47,R26,D63,R33,U87,L62,D20,R33,U53,R51',
            'U98,R91,D20,R16,D67,R40,U7,R15,U6,R7'
        ));
    }

    /** @test */
    public function wireSolverCalculatesMinStepsCrossing()
    {
        $this->assertEquals(30, WireSolver::getMinStepsCrossing(
            'R8,U5,L5,D3',
            'U7,R6,D4,L4'
        ));

        $this->assertEquals(610, WireSolver::getMinStepsCrossing(
            'R75,D30,R83,U83,L12,D49,R71,U7,L72',
            'U62,R66,U55,R34,D71,R55,D58,R83'
        ));

        $this->assertEquals(410, WireSolver::getMinStepsCrossing(
            'R98,U47,R26,D63,R33,U87,L62,D20,R33,U53,R51',
            'U98,R91,D20,R16,D67,R40,U7,R15,U6,R7'
        ));
    }
}
