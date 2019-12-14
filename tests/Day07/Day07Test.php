<?php

namespace Aoc2019\Day07;

use PHPUnit\Framework\TestCase;

class Day07Test extends TestCase
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
    public function amplificationCircuitCalculatesThrust()
    {
        $amplificationCircuit = new AmplificationCircuit('3,15,3,16,1002,16,10,16,1,16,15,15,4,15,99,0,0');
        $amplificationCircuit->setSequence([4, 3, 2, 1, 0]);
        $this->assertEquals(43210, $amplificationCircuit->getThrust());

        $amplificationCircuit = new AmplificationCircuit('3,23,3,24,1002,24,10,24,1002,23,-1,23,101,5,23,23,1,24,23,23,4,23,99,0,0');
        $amplificationCircuit->setSequence([0, 1, 2, 3, 4]);
        $this->assertEquals(54321, $amplificationCircuit->getThrust());

        $amplificationCircuit = new AmplificationCircuit('3,31,3,32,1002,32,10,32,1001,31,-2,31,1007,31,0,33,1002,33,7,33,1,33,31,31,1,32,31,31,4,31,99,0,0,0');
        $amplificationCircuit->setSequence([1, 0, 4, 3, 2]);
        $this->assertEquals(65210, $amplificationCircuit->getThrust());
    }

    /** @test */
    public function amplificationCircuitCalculatesThrustInLoopMode()
    {
        $amplificationCircuit = new AmplificationCircuit('3,26,1001,26,-4,26,3,27,1002,27,2,27,1,27,26,27,4,27,1001,28,-1,28,1005,28,6,99,0,0,5');
        $amplificationCircuit->setSequence([9, 8, 7, 6, 5]);
        $this->assertEquals(139629729, $amplificationCircuit->getThrustInLoopMode());

        $amplificationCircuit = new AmplificationCircuit('3,52,1001,52,-5,52,3,53,1,52,56,54,1007,54,5,55,1005,55,26,1001,54,-5,54,1105,1,12,1,53,54,53,1008,54,0,55,1001,55,1,55,2,53,55,53,4,53,1001,56,-1,56,1005,56,6,99,0,0,0,0,10');
        $amplificationCircuit->setSequence([9, 7, 8, 5, 6]);
        $this->assertEquals(18216, $amplificationCircuit->getThrustInLoopMode());
    }
}
