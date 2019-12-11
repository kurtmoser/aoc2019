<?php

namespace Aoc2019\Day02;

use PHPUnit\Framework\TestCase;

class Day02Test extends TestCase
{
    /** @test */
    public function intCodeComputerAdds()
    {
        $computer = new IntCodeComputer('1,0,0,0,99');
        $computer->run();
        $this->assertEquals('2,0,0,0,99', $computer->getProgram());
    }

    /** @test */
    public function intCodeComputerMultiplies()
    {
        $computer = new IntCodeComputer('2,3,0,3,99');
        $computer->run();
        $this->assertEquals('2,3,0,6,99', $computer->getProgram());

        $computer = new IntCodeComputer('2,4,4,5,99,0');
        $computer->run();
        $this->assertEquals('2,4,4,5,99,9801', $computer->getProgram());
    }

    /** @test */
    public function intCodeComputerRunsSmallProgram()
    {
        $computer = new IntCodeComputer('1,1,1,4,99,5,6,0,99');
        $computer->run();
        $this->assertEquals('30,1,1,4,2,5,6,0,99', $computer->getProgram());
    }
}
