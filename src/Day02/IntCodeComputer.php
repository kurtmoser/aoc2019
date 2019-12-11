<?php

namespace Aoc2019\Day02;

class IntCodeComputer
{
    protected $program;
    protected $ptr = 0;

    public function __construct($program = null)
    {
        if (isset($program)) {
            $this->setProgram($program);
        }
    }

    public function setProgram($program)
    {
        $this->program = preg_split('/,/', $program, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function setPointer($ptr)
    {
        $this->ptr = $ptr;
    }

    public function getProgram()
    {
        return implode(',', $this->program);
    }

    public function writeToAddress($ptr, $value)
    {
        $this->program[$ptr] = $value;
    }

    public function readFromAddress($ptr)
    {
        return $this->program[$ptr];
    }

    // Run single operation
    public function runOp()
    {
        if (!isset($this->program[$this->ptr])) {
            throw new \Exception('Missing intcode operation');
        }

        $op = (int)$this->program[$this->ptr];

        switch ($op) {
            case 1:
                $value1 = $this->program[$this->program[$this->ptr + 1]];
                $value2 = $this->program[$this->program[$this->ptr + 2]];
                $targetPtr = $this->program[$this->ptr + 3];

                $this->program[$targetPtr] = $value1 + $value2;
                $this->ptr += 4;

                break;
            case 2:
                $value1 = $this->program[$this->program[$this->ptr + 1]];
                $value2 = $this->program[$this->program[$this->ptr + 2]];
                $targetPtr = $this->program[$this->ptr + 3];

                $this->program[$targetPtr] = $value1 * $value2;
                $this->ptr += 4;

                break;
            case 99:
                $this->ptr++;

                break;
            default:
                throw new \Exception('Unknown intcode operation');
        }

        return $op;
    }

    public function run()
    {
        // echo'<pre>';print_r($this->program);echo'</pre>'.PHP_EOL;
        // return;
        do {
            $op = $this->runOp();
        } while ($op !== 99);
    }
}
