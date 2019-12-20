<?php

namespace Aoc2019\Day03;

class WireSolver
{
    public static function wireStringToPath($wireString)
    {
        $wireOps = explode(',', $wireString);
        $wirePath = [];
        $x = 0;
        $y = 0;
        $steps = 0;

        foreach ($wireOps as $wireOp) {
            $direction = substr($wireOp, 0, 1);
            $distance = substr($wireOp, 1);

            for ($i = 0; $i < $distance; $i++) {
                switch ($direction) {
                    case 'R':
                        $x++;
                        break;
                    case 'U':
                        $y++;
                        break;
                    case 'L':
                        $x--;
                        break;
                    case 'D':
                        $y--;
                        break;
                }

                $steps++;

                // If wire crosses itself we don't overwrite initial steps count
                if (!isset($wirePath[$x . ',' . $y])) {
                    $wirePath[$x . ',' . $y] = $steps;
                }
            }
        }

        return $wirePath;
    }

    public static function getMinDistanceCrossing($wireString1, $wireString2)
    {
        $wirePath1 = static::wireStringToPath($wireString1);
        $wirePath2 = static::wireStringToPath($wireString2);
        $minDistance = PHP_INT_MAX;

        foreach ($wirePath1 as $position => $steps) {
            if (isset($wirePath2[$position])) {
                $coords = explode(',', $position);
                $distance = abs($coords[0]) + abs($coords[1]);

                $minDistance = min($minDistance, $distance);
            }
        }

        return $minDistance;
    }

    public static function getMinStepsCrossing($wireString1, $wireString2)
    {
        $wirePath1 = static::wireStringToPath($wireString1);
        $wirePath2 = static::wireStringToPath($wireString2);
        $minSteps = PHP_INT_MAX;

        foreach ($wirePath1 as $position => $steps) {
            if (isset($wirePath2[$position])) {
                $steps = $wirePath1[$position] + $wirePath2[$position];

                $minSteps = min($minSteps, $steps);
            }
        }

        return $minSteps;
    }
}
