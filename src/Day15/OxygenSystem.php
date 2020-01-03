<?php

namespace Aoc2019\Day15;

use Aoc2019\Day13\IntCodeComputer;
use Aoc2019\Day18\Point;

class OxygenSystem
{
    protected $computer;

    protected $map;

    protected $oxygentLocation;

    protected $mapMinPoint;

    protected $mapMaxPoint;

    public function loadProgram($programString)
    {
        $this->computer = new IntCodeComputer($programString);
    }

    public function buildMap()
    {
        // 1 - north, 2 - south, 3 - west, 4 - east
        $directions = [1, 4, 2, 3];
        $dirIndex = 0;
        $currX = 0;
        $currY = 0;
        $maxX = 0;
        $maxY = 0;
        $minX = 0;
        $minY = 0;
        $atTheWall = false;

        while (true) {
            $this->computer->putInput($directions[$dirIndex]);
            $this->computer->runUntilOutput();

            $output = $this->computer->getOutputArray()[count($this->computer->getOutputArray()) - 1];

            if ($output === 1) {         // we could move in the direction we wanted
                if ($directions[$dirIndex] === 1) {
                    $currY++;
                    $maxY = max($maxY, $currY);
                } elseif ($directions[$dirIndex] === 2) {
                    $currY--;
                    $minY = min($minY, $currY);
                } elseif ($directions[$dirIndex] === 3) {
                    $currX--;
                    $minX = min($minX, $currX);
                } elseif ($directions[$dirIndex] === 4) {
                    $currX++;
                    $maxX = max($maxX, $currX);
                }

                // Check if we have returned to starting point
                if ($currX === 0 && $currY === 0) {
                    break;
                }

                $map[$currX][$currY] = '.';

                // Try turning left
                if ($atTheWall) {
                    $dirIndex--;
                    if ($dirIndex < 0) {
                        $dirIndex += 4;
                    }
                } else {
                    continue;
                }
            } elseif ($output == 0) {   // Hit wall
                $atTheWall = true;

                // Mark wall on the map
                if ($directions[$dirIndex] == 1) {
                    $map[$currX][$currY + 1] = '#';
                    $maxY = ($maxY > ($currY + 1)) ? $maxY : $currY + 1;
                } elseif ($directions[$dirIndex] == 2) {
                    $map[$currX][$currY - 1] = '#';
                    $minY = ($minY < ($currY - 1)) ? $minY : $currY - 1;
                } elseif ($directions[$dirIndex] == 3) {
                    $map[$currX - 1][$currY] = '#';
                    $minX = ($minX < ($currX - 1)) ? $minX : $currX - 1;
                } elseif ($directions[$dirIndex] == 4) {
                    $map[$currX + 1][$currY] = '#';
                    $maxX = ($maxX > ($currX + 1)) ? $maxX : $currX + 1;
                }

                $dirIndex++;
                if ($dirIndex > 3) {
                    $dirIndex -= 4;
                }
            } elseif ($output == 2) {
                // Encountered the searched item
                if ($directions[$dirIndex] == 1) {
                    $currY++;
                    $maxY = ($maxY > $currY) ? $maxY : $currY;
                } elseif ($directions[$dirIndex] == 2) {
                    $currY--;
                    $minY = ($minY < $currY) ? $minY : $currY;
                } elseif ($directions[$dirIndex] == 3) {
                    $currX--;
                    $minX = ($minX < $currX) ? $minX : $currX;
                } elseif ($directions[$dirIndex] == 4) {
                    $currX++;
                    $maxX = ($maxX > $currX) ? $maxX : $currX;
                }

                $this->oxygentLocation = new Point($currX, $currY);

                $map[$currX][$currY] = '2';
            }
        }

        for ($x = $minX; $x <= $maxX; $x++) {
            for ($y = $minY; $y <= $maxY; $y++) {
                if (!isset($map[$x][$y])) {
                    $map[$x][$y] = '#';
                }
            }
        }

        $this->map = $map;
        $this->mapMinPoint = new Point($minX, $minY);
        $this->mapMaxPoint = new Point($maxX, $maxY);
    }

    public function findMinPathToOxygen()
    {
        $xNum = [-1, 0, 0, 1];
        $yNum = [0, -1, 1, 0];

        $visited = [];
        for ($y = $this->mapMinPoint->y; $y <= $this->mapMaxPoint->y; $y++) {
            for ($x = $this->mapMinPoint->x; $x <= $this->mapMaxPoint->x; $x++) {
                $visited[$x][$y] = false;
            }
        }

        $visited[0][0] = true;

        $queue = [];
        array_push($queue, [
            'point' => new Point(0, 0),
            'dist' => 0,
        ]);

        while (count($queue)) {
            $data = $queue[0];

            if ($data['point']->x === $this->oxygentLocation->x
                && $data['point']->y === $this->oxygentLocation->y
            ) {
                return $data['dist'];
            }

            array_shift($queue);

            for ($i = 0; $i < 4; $i++) {
                $x = $data['point']->x + $xNum[$i];
                $y = $data['point']->y + $yNum[$i];

                if ($x < $this->mapMinPoint->x || $x > $this->mapMaxPoint->x
                    || $y < $this->mapMinPoint->y || $y > $this->mapMaxPoint->y
                ) {
                    continue;
                }

                if ($visited[$x][$y]) {
                    continue;
                }

                if ($this->map[$x][$y] === '#') {
                    continue;
                }

                $visited[$x][$y] = true;

                array_push($queue, [
                    'point' => new Point($x, $y),
                    'dist' => $data['dist'] + 1,
                ]);
            }
        }

        return -1;
    }

    public function findTimeToFillMapWithOxygen()
    {
        $map = $this->map;
        $step = 0;

        while (!$this->isMapFilledWithOxygen($map)) {
            $map = $this->spreadOxygen($map);
            $step++;
        }

        return $step;
    }

    protected function spreadOxygen($map)
    {
        for ($x = $this->mapMinPoint->x + 1; $x < $this->mapMaxPoint->x; $x++) {
            for ($y = $this->mapMinPoint->y + 1; $y < $this->mapMaxPoint->y; $y++) {
                if ($map[$x][$y] === '2') {
                    if ($map[$x - 1][$y] === '.') {
                        $map[$x - 1][$y] = '1';
                    }
                    if ($map[$x + 1][$y] === '.') {
                        $map[$x + 1][$y] = '1';
                    }
                    if ($map[$x][$y - 1] === '.') {
                        $map[$x][$y - 1] = '1';
                    }
                    if ($map[$x][$y + 1] === '.') {
                        $map[$x][$y + 1] = '1';
                    }
                }
            }
        }

        for ($x = $this->mapMinPoint->x + 1; $x < $this->mapMaxPoint->x; $x++) {
            for ($y = $this->mapMinPoint->y + 1; $y < $this->mapMaxPoint->y; $y++) {
                if ($map[$x][$y] === '1') {
                    $map[$x][$y] = '2';
                }
            }
        }

        return $map;
    }

    protected function isMapFilledWithOxygen($map)
    {
        for ($x = $this->mapMinPoint->x + 1; $x < $this->mapMaxPoint->x; $x++) {
            for ($y = $this->mapMinPoint->y + 1; $y < $this->mapMaxPoint->y; $y++) {
                if ($map[$x][$y] === '.') {
                    return false;
                }
            }
        }

        return true;
    }
}
