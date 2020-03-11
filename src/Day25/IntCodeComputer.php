<?php

namespace Aoc2019\Day25;

class IntCodeComputer
{
    protected $program = [];

    protected $ptr = 0;

    protected $relativePtr = 0;

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
            return $this->program[$this->getAddress($ptr, $mode)] ?? 0;
        }
    }

    public function getAddress($ptr, $mode)
    {
        if ($mode === 2) {
            return $this->relativePtr + $this->program[$ptr];
        } else {
            return $this->program[$ptr];
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
                $targetPtr = $this->getAddress($this->ptr + 3, $parameterModes[2]);

                $this->program[$targetPtr] = $value1 + $value2;
                $this->ptr += 4;

                break;
            case 2:
                $value1 = $this->getValue($this->ptr + 1, $parameterModes[0]);
                $value2 = $this->getValue($this->ptr + 2, $parameterModes[1]);
                $targetPtr = $this->getAddress($this->ptr + 3, $parameterModes[2]);

                $this->program[$targetPtr] = $value1 * $value2;
                $this->ptr += 4;

                break;
            case 3:
                $targetPtr = $this->getAddress($this->ptr + 1, $parameterModes[0]);

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
                $targetPtr = $this->getAddress($this->ptr + 3, $parameterModes[2]);

                $this->program[$targetPtr] = (int)($value1 < $value2);
                $this->ptr += 4;

                break;
            case 8:     // EQ
                $value1 = $this->getValue($this->ptr + 1, $parameterModes[0]);
                $value2 = $this->getValue($this->ptr + 2, $parameterModes[1]);
                $targetPtr = $this->getAddress($this->ptr + 3, $parameterModes[2]);

                $this->program[$targetPtr] = (int)($value1 === $value2);
                $this->ptr += 4;

                break;
            case 9:     // REL
                $value1 = $this->getValue($this->ptr + 1, $parameterModes[0]);

                $this->relativePtr += $value1;
                $this->ptr += 2;

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

    public function runUntilOutput()
    {
        return $this->runUntilOp(4);
    }

    public function getNextOp()
    {
        $fullOp = str_pad($this->program[$this->ptr], 8, '0', STR_PAD_LEFT);
        $op = (int)substr($fullOp, -2);

        return $op;
    }

    public function pauseBeforeOp($pauseOps)
    {
        do {
            if (in_array($this->getNextOp(), $pauseOps)) {
                return $this->getNextOp();  // Actually return currentOp
            }

            $op = $this->runOp();
        } while ($op !== 99);

        return $op;
    }

    public function pauseBeforeInput()
    {
        return $this->pauseBeforeOp(3);
    }

    public function putAsciiInput($input)
    {
        $input = trim($input);

        for ($i = 0; $i < strlen($input); $i++) {
            $this->putInput(ord($input[$i]));
        }

        $this->putInput(10);
    }

    public function getAsciiOutput()
    {
        $outputString = '';

        for ($i = 0; $i < count($this->output); $i++) {
            $outputString .= chr($this->output[$i]);
        }

        return $outputString;
    }

    public function emptyOutput()
    {
        $this->output = [];
    }
}
