<?php

namespace Aoc2019\Day17;

use Aoc2019\Day13\IntCodeComputer;

class ScaffoldRunner
{
    protected $computer;

    protected $map;

    public function loadProgram($programString)
    {
        $this->computer = new IntCodeComputer($programString);
    }

    public function buildMap()
    {
        $mapString = '';

        while (true) {
            $opCode = $this->computer->pauseBeforeOp([4]);

            if ($opCode === 99) {
                break;
            }

            $this->computer->runOp();
            $asciiCode = $this->computer->getOutputArray()[count($this->computer->getOutputArray()) - 1];

            $mapString .= chr($asciiCode);
        }

        $mapLines = explode(PHP_EOL, trim($mapString));
        $this->map = [];

        foreach ($mapLines as $y => $mapLine) {
            for ($x = 0; $x < strlen($mapLine); $x++) {
                $this->map[$x][$y] = $mapLine[$x];
            }
        }
    }

    public function getCrossingCoordinatesSum()
    {
        $sum = 0;

        for ($x = 1; $x < count($this->map) - 1; $x++) {
            for ($y = 1; $y < count($this->map[0]) - 1; $y++) {
                if ($this->map[$x][$y] === '#'
                    && $this->map[$x - 1][$y] === '#'
                    && $this->map[$x + 1][$y] === '#'
                    && $this->map[$x][$y - 1] === '#'
                    && $this->map[$x][$y + 1] === '#'
                ) {
                    $sum += $x * $y;
                }
            }
        }

        return $sum;
    }
}
