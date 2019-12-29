<?php

namespace Aoc2019\Day10;

class MontioringStation
{
    protected $map;

    public function loadMap($mapString)
    {
        $mapLines = explode("\n", $mapString);

        for ($y = 0; $y < count($mapLines); $y++) {
            for ($x = 0; $x < strlen($mapLines[$y]); $x++) {
                $this->map[$x][$y] = $mapLines[$y][$x];

                if ($this->map[$x][$y] == '#') {
                    $this->asteroids[] = [
                        'x' => $x,
                        'y' => $y,
                    ];
                }
            }
        }
    }

    public function calculateAsteroidDistances()
    {
        for ($i = 0; $i < count($this->asteroids) - 1; $i++) {
            for ($j = $i + 1; $j < count($this->asteroids); $j++) {
                $xdist = $this->asteroids[$j]['x'] - $this->asteroids[$i]['x'];
                $ydist = $this->asteroids[$j]['y'] - $this->asteroids[$i]['y'];
                $ydist = -$ydist;

                $xdistOrig = $xdist;
                $ydistOrig = $ydist;

                $divisor = (int)gmp_gcd($xdist, $ydist);

                $xdist /= $divisor;
                $ydist /= $divisor;

                if (!isset($this->asteroids[$i]['direction_containing_asteroids'][$xdist . ',' . $ydist])) {
                    $this->asteroids[$i]['direction_containing_asteroids'][$xdist . ',' . $ydist] = [];
                }

                $this->asteroids[$i]['direction_containing_asteroids'][$xdist . ',' . $ydist][] = [
                    'x' => $this->asteroids[$j]['x'],
                    'y' => $this->asteroids[$j]['y'],
                    'distance' => ($xdistOrig ** 2) + ($ydistOrig ** 2),
                ];

                if (!isset($this->asteroids[$j]['direction_containing_asteroids'][-$xdist . ',' . -$ydist])) {
                    $this->asteroids[$j]['direction_containing_asteroids'][-$xdist . ',' . -$ydist] = [];
                }

                $this->asteroids[$j]['direction_containing_asteroids'][-$xdist . ',' . -$ydist][] = [
                    'x' => $this->asteroids[$i]['x'],
                    'y' => $this->asteroids[$i]['y'],
                    'distance' => ($xdistOrig ** 2) + ($ydistOrig ** 2),
                ];
            }
        }
    }

    public function getAsteroidWithMostVisibleNeighbors()
    {
        $maxVisibleAsteroids = 0;
        $bestAsteroid = null;

        foreach ($this->asteroids as $asteroid) {
            if (count($asteroid['direction_containing_asteroids']) > $maxVisibleAsteroids) {
                $maxVisibleAsteroids = count($asteroid['direction_containing_asteroids']);
                $bestAsteroid = $asteroid;
            }
        }

        return $bestAsteroid;
    }

    public function findNthAsteroidCoordinates($baseAsteroid, $n)
    {
        $angleArray = [];

        foreach ($baseAsteroid['direction_containing_asteroids'] as $key => $direction) {
            usort($baseAsteroid['direction_containing_asteroids'][$key], function ($a, $b) {
                return $a['distance'] > $b['distance'];
            });

            [$xdist, $ydist] = explode(',', $key);

            $angle = atan2($xdist, $ydist);
            if ($angle < 0) {
                $angle = (2 * M_PI) + $angle;
            }

            $keyVal = (int)($angle * 36000 / (2 * M_PI));
            $angleArray[$keyVal] = $key;
        }

        ksort($angleArray);
        $angleArray = array_values($angleArray);

        $orderedAsteroids = [];

        while (true) {
            $foundAny = false;

            for ($i = 0; $i < count($angleArray); $i++) {
                if (count($baseAsteroid['direction_containing_asteroids'][$angleArray[$i]])) {
                    $orderedAsteroids[] = array_shift($baseAsteroid['direction_containing_asteroids'][$angleArray[$i]]);
                    $foundAny = true;
                }
            }

            if (!$foundAny) {
                break;
            }
        }

        return $orderedAsteroids[$n - 1]['x'] * 100 + $orderedAsteroids[$n - 1]['y'];
    }
}
