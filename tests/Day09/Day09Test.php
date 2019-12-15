<?php

namespace Aoc2019\Day09;

use PHPUnit\Framework\TestCase;

class Day09Test extends TestCase
{
    /** @test */
    public function intCodeComputerSupportsAddOperation()
    {
        $computer = new IntCodeComputer('1,0,0,0,99');
        $computer->run();
        $this->assertEquals('2,0,0,0,99', $computer->getProgram());
    }

    /** @test */
    public function intCodeComputerSupportsMultiplyOperation()
    {
        $computer = new IntCodeComputer('2,3,0,3,99');
        $computer->run();
        $this->assertEquals('2,3,0,6,99', $computer->getProgram());

        $computer = new IntCodeComputer('2,4,4,5,99,0');
        $computer->run();
        $this->assertEquals('2,4,4,5,99,9801', $computer->getProgram());
    }

    /** @test */
    public function intCodeComputerHandlesSmallProgram()
    {
        $computer = new IntCodeComputer('1,1,1,4,99,5,6,0,99');
        $computer->run();
        $this->assertEquals('30,1,1,4,2,5,6,0,99', $computer->getProgram());
    }

    /** @test */
    public function intCodeComputerSupportsImmediateMode()
    {
        $computer = new IntCodeComputer('1002,4,3,4,33');
        $computer->run();
        $this->assertEquals(99, $computer->readFromAddress(4));
    }

    /** @test */
    public function intCodeComputerHandlesNegativeValueInImmediateMode()
    {
        $computer = new IntCodeComputer('1101,100,-1,4,0');
        $computer->run();
        $this->assertEquals(99, $computer->readFromAddress(4));
    }

    /** @test */
    public function intCodeComputerSupportsJumpOperation()
    {
        // Program checks whether input value <8

        // Position mode
        $computer = new IntCodeComputer('3,12,6,12,15,1,13,14,13,4,13,99,-1,0,1,9');
        $computer->putInput(0);
        $computer->run();
        $this->assertEquals(0, $computer->getOutputArray()[0]);

        $computer = new IntCodeComputer('3,12,6,12,15,1,13,14,13,4,13,99,-1,0,1,9');
        $computer->putInput(100);
        $computer->run();
        $this->assertEquals(1, $computer->getOutputArray()[0]);

        // Immediate mode
        $computer = new IntCodeComputer('3,3,1105,-1,9,1101,0,0,12,4,12,99,1');
        $computer->putInput(0);
        $computer->run();
        $this->assertEquals(0, $computer->getOutputArray()[0]);

        $computer = new IntCodeComputer('3,3,1105,-1,9,1101,0,0,12,4,12,99,1');
        $computer->putInput(100);
        $computer->run();
        $this->assertEquals(1, $computer->getOutputArray()[0]);
    }

    /** @test */
    public function intCodeComputerSupportsEqualsOperation()
    {
        // Program checks whether input value equals to 8

        // Position mode
        $computer = new IntCodeComputer('3,9,8,9,10,9,4,9,99,-1,8');
        $computer->putInput(8);
        $computer->run();
        $this->assertEquals(1, $computer->getOutputArray()[0]);

        $computer = new IntCodeComputer('3,9,8,9,10,9,4,9,99,-1,8');
        $computer->putInput(9);
        $computer->run();
        $this->assertEquals(0, $computer->getOutputArray()[0]);

        // Immediate mode
        $computer = new IntCodeComputer('3,3,1108,-1,8,3,4,3,99');
        $computer->putInput(8);
        $computer->run();
        $this->assertEquals(1, $computer->getOutputArray()[0]);

        $computer = new IntCodeComputer('3,3,1108,-1,8,3,4,3,99');
        $computer->putInput(9);
        $computer->run();
        $this->assertEquals(0, $computer->getOutputArray()[0]);

    }

    /** @test */
    public function intCodeComputerSupportsLessThanOperation()
    {
        // Program checks whether input value less than 8

        // Position mode
        $computer = new IntCodeComputer('3,9,7,9,10,9,4,9,99,-1,8');
        $computer->putInput(7);
        $computer->run();
        $this->assertEquals(1, $computer->getOutputArray()[0]);

        $computer = new IntCodeComputer('3,9,7,9,10,9,4,9,99,-1,8');
        $computer->putInput(8);
        $computer->run();
        $this->assertEquals(0, $computer->getOutputArray()[0]);

        // Immediate mode
        $computer = new IntCodeComputer('3,3,1107,-1,8,3,4,3,99');
        $computer->putInput(7);
        $computer->run();
        $this->assertEquals(1, $computer->getOutputArray()[0]);

        $computer = new IntCodeComputer('3,3,1107,-1,8,3,4,3,99');
        $computer->putInput(8);
        $computer->run();
        $this->assertEquals(0, $computer->getOutputArray()[0]);
    }

    /** @test */
    public function intCodeComputerHandlesCompareJumpProgram()
    {
        // Program returns 999 if input less than 8
        $computer = new IntCodeComputer('3,21,1008,21,8,20,1005,20,22,107,8,21,20,1006,20,31,1106,0,36,98,0,0,1002,21,125,20,4,20,1105,1,46,104,999,1105,1,46,1101,1000,1,20,4,20,1105,1,46,98,99');
        $computer->putInput(1);
        $computer->run();
        $this->assertEquals(999, $computer->getOutputArray()[0]);

        // Program returns 1000 if input equals to 8
        $computer = new IntCodeComputer('3,21,1008,21,8,20,1005,20,22,107,8,21,20,1006,20,31,1106,0,36,98,0,0,1002,21,125,20,4,20,1105,1,46,104,999,1105,1,46,1101,1000,1,20,4,20,1105,1,46,98,99');
        $computer->putInput(8);
        $computer->run();
        $this->assertEquals(1000, $computer->getOutputArray()[0]);

        // Program returns 1001 if input greater than 8
        $computer = new IntCodeComputer('3,21,1008,21,8,20,1005,20,22,107,8,21,20,1006,20,31,1106,0,36,98,0,0,1002,21,125,20,4,20,1105,1,46,104,999,1105,1,46,1101,1000,1,20,4,20,1105,1,46,98,99');
        $computer->putInput(100);
        $computer->run();
        $this->assertEquals(1001, $computer->getOutputArray()[0]);
    }

    /** @test */
    public function intCodeComputerSupportsRelativeMode()
    {
        $computer = new IntCodeComputer('109,1,204,-1,1001,100,1,100,1008,100,16,101,1006,101,0,99');
        $computer->run();
        $this->assertEquals('109,1,204,-1,1001,100,1,100,1008,100,16,101,1006,101,0,99', implode(',', $computer->getOutputArray()));
    }
}
