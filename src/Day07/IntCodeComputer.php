<?php

namespace Aoc2019\Day07;

class IntCodeComputer
{
    protected $program = [];
    protected $ptr = 0;
    protected $input = [];
    protected $output = [];

    public function __construct($program = null)
    {
        if (isset($program)) {
            $this->setProgram($program);
        }
    }

    public function setProgram($program)
    {
        $this->program = preg_split('/,/', $program, -1, PREG_SPLIT_NO_EMPTY);

        array_walk($this->program, function (&$item) {
            $item = (int)$item;
        });
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

    public function getValue($ptr, $mode)
    {
        if ($mode === 1) {
            return $this->program[$ptr];
        } else {
            return $this->program[$this->program[$ptr]];
        }
    }

    public function getInput()
    {
        return array_shift($this->input);
    }

    public function putInput($value)
    {
        array_push($this->input, $value);
    }

    // Return entire output array
    public function getOutputArray()
    {
        return $this->output;
    }

    // Add single value to the end of output array
    public function putOutput($value)
    {
        array_push($this->output, $value);
    }

    // Run single operation
    public function runOp()
    {
        if (!isset($this->program[$this->ptr])) {
            throw new \Exception('Missing intcode operation');
        }

        $fullOp = str_pad($this->program[$this->ptr], 8, '0', STR_PAD_LEFT);
        $op = (int)substr($fullOp, -2);
        $parameterModes = [];
        for ($i = 5; $i >= 0; $i--) {
            $parameterModes[] = (int)substr($fullOp, $i, 1);
        }

        switch ($op) {
            case 1:
                $value1 = $this->getValue($this->ptr + 1, $parameterModes[0]);
                $value2 = $this->getValue($this->ptr + 2, $parameterModes[1]);
                $targetPtr = $this->program[$this->ptr + 3];

                $this->program[$targetPtr] = $value1 + $value2;
                $this->ptr += 4;

                break;
            case 2:
                $value1 = $this->getValue($this->ptr + 1, $parameterModes[0]);
                $value2 = $this->getValue($this->ptr + 2, $parameterModes[1]);
                $targetPtr = $this->program[$this->ptr + 3];

                $this->program[$targetPtr] = $value1 * $value2;
                $this->ptr += 4;

                break;
            case 3:
                $targetPtr = $this->program[$this->ptr + 1];

                $this->program[$targetPtr] = $this->getInput();
                $this->ptr += 2;

                break;
            case 4:
                $value = $this->getValue($this->ptr + 1, $parameterModes[0]);

                $this->putOutput($value);
                $this->ptr += 2;

                break;
            case 5:
                $value1 = $this->getValue($this->ptr + 1, $parameterModes[0]);
                $value2 = $this->getValue($this->ptr + 2, $parameterModes[1]);

                if ($value1 !== 0) {
                    $this->ptr = $value2;
                } else {
                    $this->ptr += 3;
                }

                break;
            case 6:
                $value1 = $this->getValue($this->ptr + 1, $parameterModes[0]);
                $value2 = $this->getValue($this->ptr + 2, $parameterModes[1]);

                if ($value1 === 0) {
                    $this->ptr = $value2;
                } else {
                    $this->ptr += 3;
                }

                break;
            case 7:     // LT
                $value1 = $this->getValue($this->ptr + 1, $parameterModes[0]);
                $value2 = $this->getValue($this->ptr + 2, $parameterModes[1]);
                $targetPtr = $this->program[$this->ptr + 3];

                $this->program[$targetPtr] = (int)($value1 < $value2);
                $this->ptr += 4;

                break;
            case 8:     // EQ
                $value1 = $this->getValue($this->ptr + 1, $parameterModes[0]);
                $value2 = $this->getValue($this->ptr + 2, $parameterModes[1]);
                $targetPtr = $this->program[$this->ptr + 3];

                $this->program[$targetPtr] = (int)($value1 === $value2);
                $this->ptr += 4;

                break;
            case 99:
                $this->ptr++;

                break;
            default:
                throw new \Exception('Unknown intcode operation: ' . $op);
        }

        return $op;
    }

    public function run()
    {
        do {
            $op = $this->runOp();
        } while ($op !== 99);

        return $op;
    }

    public function runUntilOp($stopOp)
    {
        do {
            $op = $this->runOp();
        } while ($op !== 99 && $op !== $stopOp);

        return $op;
    }
}
