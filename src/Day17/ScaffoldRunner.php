<?php

namespace Aoc2019\Day17;

use Aoc2019\Day13\IntCodeComputer;

class ScaffoldRunner
{
    protected $computer;

    protected $programString;

    protected $map;

    public function loadProgram($programString)
    {
        $this->computer = new IntCodeComputer($programString);

        $this->programString = $programString;
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

    public function getCollectedDustAmount()
    {
        $directions = [
            [0, -1],
            [1, 0],
            [0, 1],
            [-1, 0],
        ];

        $currX = 0;
        $currY = 0;
        $speedX = 0;
        $speedY = 0;

        for ($x = 0; $x < count($this->map); $x++) {
            for ($y = 0; $y < count($this->map[0]); $y++) {
                if (in_array($this->map[$x][$y], ['^', 'v', '<', '>'])) {
                    $currX = $x;
                    $currY = $y;

                    switch ($this->map[$x][$y]) {
                        case '^':
                            $currDir = 0;
                            break;
                        case '>':
                            $currDir = 1;
                            break;
                        case 'v':
                            $currDir = 2;
                            break;
                        case '<':
                            $currDir = 3;
                            break;
                    }
                }
            }
        }

        $moves = [];

        while (true) {
            $turnDirection = $this->getTurnDirection($currX, $currY, $directions[$currDir][0], $directions[$currDir][1]);
            if ($turnDirection != 'R' && $turnDirection != 'L') {
                break;
            }

            if ($turnDirection == 'L') {
                $currDir--;
                if ($currDir < 0) {
                    $currDir += 4;
                }
            } elseif ($turnDirection == 'R') {
                $currDir++;
                if ($currDir >= 4) {
                    $currDir -= 4;
                }
            }

            $steps = $this->moveUntilWall($currX, $currY, $directions[$currDir][0], $directions[$currDir][1]);

            $currX += $directions[$currDir][0] * $steps;
            $currY += $directions[$currDir][1] * $steps;

            $moves[] = $turnDirection . ',' . $steps . ',';
        }

        $computedInstruction = $this->computeOptimalInstructions($moves);

        $modifiedComputer = new IntCodeComputer('2' . substr($this->programString, 1));

        for ($i = 0; $i < count($computedInstruction); $i++) {
            for ($j = 0; $j < strlen($computedInstruction[$i]); $j++) {
                $modifiedComputer->putInput(ord($computedInstruction[$i][$j]));
            }
            $modifiedComputer->putInput(ord("\n"));
        }

        // As last setup instruction say that we don't need feedback
        $modifiedComputer->putInput(ord('n'));
        $modifiedComputer->putInput(ord("\n"));

        $modifiedComputer->run();

        return $modifiedComputer->getOutputArray()[count($modifiedComputer->getOutputArray()) - 1];
    }

    protected function computeOptimalInstructions($moves)
    {
        // Iterate over all possible subroutine combinations. We will have
        // duplicates, but that's ok. Keep in mind that there may be offsets
        // between subroutines i.e. subroutine B does not need to start
        // immediately where we stopped building subroutine A. Hence offsetA
        // and offsetB variables.

        for ($lenA = 1; $lenA < count($moves); $lenA++) {
            if ($lenA > count($moves)) {
                continue;
            }

            $programA = '';
            for ($i = 0; $i < $lenA; $i++) {
                $programA .= $moves[$i];
            }
            $programA = rtrim($programA, ',');

            if (strlen($programA) > 20) {
                continue;
            }

            for ($offsetA = 0; $offsetA < count($moves); $offsetA++) {
                if ($lenA + $offsetA > count($moves)) {
                    continue;
                }

                for ($lenB = 1; $lenB < count($moves); $lenB++) {
                    if ($lenA + $offsetA + $lenB > count($moves)) {
                        continue;
                    }

                    $programB = '';
                    for ($i = 0; $i < $lenB; $i++) {
                        $programB .= $moves[$i + $lenA + $offsetA];
                    }
                    $programB = rtrim($programB, ',');

                    if (strlen($programB) > 20) {
                        continue;
                    }

                    for ($offsetB = 0; $offsetB < count($moves); $offsetB++) {
                        if ($lenA + $offsetA + $lenB + $offsetB > count($moves)) {
                            continue;
                        }

                        for ($lenC = 1; $lenC < count($moves); $lenC++) {
                            if ($lenA + $offsetA + $lenB + $offsetB + $lenC > count($moves)) {
                                continue;
                            }

                            $programC = '';
                            for ($i = 0; $i < $lenC; $i++) {
                                $programC .= $moves[$i + $lenA + $offsetA + $lenB + $offsetB];
                            }
                            $programC = rtrim($programC, ',');

                            if (strlen($programC) > 20) {
                                continue;
                            }

                            // Sort programs by length, we want to replace
                            // biggest chunks first
                            $programs = [$programA, $programB, $programC];
                            usort($programs, function ($a, $b) {
                                return strlen($a) < strlen($b);
                            });

                            $source = implode('', $moves);

                            foreach ($programs as $key => $p) {
                                $source = str_replace($p, chr($key + 65), $source);
                            }

                            $source = rtrim($source, ',');

                            if (strlen($source) <= 20) {
                                array_unshift($programs, $source);
                                return $programs;
                            }
                        }
                    }
                }
            }
        }
    }

    protected function getTurnDirection($x, $y, $sx, $sy)
    {
        if ($sx != 0) {
            if (($y - 1) >= 0 && $this->map[$x][$y - 1] == '#') {
                return ($sx == 1) ? 'L' : 'R';
            } elseif (($y + 1) < count($this->map[0]) && $this->map[$x][$y + 1] == '#') {
                return ($sx == 1) ? 'R' : 'L';
            } else {
                return '?';
            }
        } elseif ($sy != 0) {
            if (($x - 1) >= 0 && $this->map[$x - 1][$y] == '#') {
                return ($sy == -1) ? 'L' : 'R';
            } elseif (($x + 1) < count($this->map) && $this->map[$x + 1][$y] == '#') {
                return ($sy == -1) ? 'R' : 'L';
            } else {
                return '?';
            }
        }

        return '?';
    }

    protected function moveUntilWall($x, $y, $sx, $sy)
    {
        $steps = 0;

        while (true) {
            // Correct order: increment coords, check, increment steps
            $x += $sx;
            $y += $sy;

            if ($x < 0 || $x >= count($this->map)) {
                break;
            }

            if ($y < 0 || $y >= count($this->map[0])) {
                break;
            }

            if ($this->map[$x][$y] != '#') {
                break;
            }

            $steps++;
        }

        return $steps;
    }
}
