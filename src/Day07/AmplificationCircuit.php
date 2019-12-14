<?php

namespace Aoc2019\Day07;

class AmplificationCircuit
{
    public $sequence;

    public $computers;

    public function __construct($program)
    {
        $this->computers = [];

        for ($i = 0; $i < 5; $i++) {
            $this->computers[] = new IntCodeComputer($program);
        }
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    public function getThrust()
    {
        $input = 0;

        for ($i = 0; $i < 5; $i++) {
            $this->computers[$i]->putInput($this->sequence[$i]);
            $this->computers[$i]->putInput($input);
            $this->computers[$i]->run();
            $input = $this->computers[$i]->getOutputArray()[0];
        }

        return $this->computers[4]->getOutputArray()[0];
    }

    public function getThrustInLoopMode()
    {
        $input = 0;

        for ($i = 0; $i < 5; $i++) {
            $this->computers[$i]->putInput($this->sequence[$i]);
        }

        $this->computers[0]->putInput(0);
        while (true) {
            for ($i = 0; $i < 5; $i++) {
                $lastOp = $this->computers[$i]->runUntilOp(4);
                $input = $this->computers[$i]->getOutputArray()[count($this->computers[$i]->getOutputArray()) - 1];
                $this->computers[($i + 1) % 5]->putInput($input);
            }

            if ($lastOp === 99) {
                break;
            }
        }

        return $this->computers[4]->getOutputArray()[count($this->computers[4]->getOutputArray()) - 1];
    }
}
