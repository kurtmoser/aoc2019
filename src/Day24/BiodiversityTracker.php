<?php

namespace Aoc2019\Day24;

class BiodiversityTracker
{
    protected $map;

    // Handpicked minimum necessary level depth for 200 iterations in part 2
    protected $depth = 101;

    public function loadMap($mapString)
    {
        $mapLines = explode("\n", $mapString);

        for ($y = 0; $y < count($mapLines); $y++) {
            if (strlen($mapLines[$y]) == 0) {
                continue;
            }

            for ($x = 0; $x < strlen($mapLines[$y]); $x++) {
                $this->map[0][$y][$x] = $mapLines[$y][$x];

                for ($level = 1; $level <= $this->depth; $level++) {
                    $this->map[$level][$y][$x] = '.';
                    $this->map[-$level][$y][$x] = '.';
                }
            }
        }
    }

    public function evolve($map, $recursively = false)
    {
        $evolvedMap = $map;

        for ($level = -$this->depth + 1; $level <= $this->depth - 1; $level++) {
            for ($y = 0; $y < count($map[$level]); $y++) {
                for ($x = 0; $x < count($map[$level][$y]); $x++) {
                    if ($recursively) {
                        if ($x === 2 && $y === 2) {
                            continue;
                        }

                        $adjacentTiles = $this->getAdjacentTilesRecursively($level, $x, $y);
                    } else {
                        $adjacentTiles = $this->getAdjacentTiles($x, $y);
                    }

                    $numBugsOnAdjacents = 0;
                    foreach ($adjacentTiles as $adjacent) {
                        [$adjacentLevel, $adjacentX, $adjacentY] = explode(',', $adjacent);

                        if ($map[$adjacentLevel][$adjacentY][$adjacentX] === '#') {
                            $numBugsOnAdjacents++;
                        }
                    }

                    if ($map[$level][$y][$x] == '#' && $numBugsOnAdjacents != 1) {
                        $evolvedMap[$level][$y][$x] = '.';
                    }

                    if ($map[$level][$y][$x] == '.' && ($numBugsOnAdjacents == 1 || $numBugsOnAdjacents == 2)) {
                        $evolvedMap[$level][$y][$x] = '#';
                    }
                }
            }
        }

        return $evolvedMap;
    }

    public function calculateVariation($map, $level = 0)
    {
        $variation = 0;
        $multiplier = 1;

        for ($y = 0; $y < count($map[$level]); $y++) {
            for ($x = 0; $x < count($map[$level][$y]); $x++) {
                if ($map[$level][$y][$x] == '#') {
                    $variation += $multiplier;
                }

                $multiplier *= 2;
            }
        }

        return $variation;
    }

    public function findFirstRepeatingVariation()
    {
        $step = 0;
        $variations = [];

        while (true) {
            $variation = $this->calculateVariation($this->map);

            if (isset($variations[$variation])) {
                return $variation;
            } else {
                $variations[$variation] = true;
            }

            $this->map = $this->evolve($this->map);
        }
    }

    public function countBugs($map)
    {
        $numBugs = 0;

        for ($level = -$this->depth; $level <= $this->depth; $level++) {
            for ($y = 0; $y < count($map[$level]); $y++) {
                for ($x = 0; $x < count($map[$level][$y]); $x++) {
                    if ($map[$level][$y][$x] == '#') {
                        $numBugs++;
                    }
                }
            }
        }

        return $numBugs;
    }

    public function countBugsAfterEvolution($numEvolutions)
    {
        for ($i = 0; $i < $numEvolutions; $i++) {
            $this->map = $this->evolve($this->map, true);
        }

        return $this->countBugs($this->map);
    }

    public function getAdjacentTiles($x, $y)
    {
        $level = 0;

        if ($x > 0) {
            $adjacentTiles[] = '' . $level . ',' . ($x - 1) . ',' . $y;
        }
        if ($x < 4) {
            $adjacentTiles[] = '' . $level . ',' . ($x + 1) . ',' . $y;
        }
        if ($y > 0) {
            $adjacentTiles[] = '' . $level . ',' . $x . ',' . ($y - 1);
        }
        if ($y < 4) {
            $adjacentTiles[] = '' . $level . ',' . $x . ',' . ($y + 1);
        }

        return $adjacentTiles;
    }

    protected function getAdjacentTilesRecursively($level, $x, $y)
    {
        $adjacentTiles = [];

        if ($x > 0) {
            if (!($x == 3 && $y == 2)) {
                $adjacentTiles[] = '' . $level . ',' . ($x - 1) . ',' . $y;
            }
        }
        if ($x < 4) {
            if (!($x == 1 && $y == 2)) {
                $adjacentTiles[] = '' . $level . ',' . ($x + 1) . ',' . $y;
            }
        }
        if ($y > 0) {
            if (!($y == 3 && $x == 2)) {
                $adjacentTiles[] = '' . $level . ',' . $x . ',' . ($y - 1);
            }
        }
        if ($y < 4) {
            if (!($y == 1 && $x == 2)) {
                $adjacentTiles[] = '' . $level . ',' . $x . ',' . ($y + 1);
            }
        }


        if ($x == 0) {
            $adjacentTiles[] = '' . ($level - 1) . ',1,2';
        } elseif ($x == 1 && $y == 2) {
            for ($i = 0; $i < 5; $i++) {
                $adjacentTiles[] = '' . ($level + 1) . ',0,' . $i;
            }
        } elseif ($x == 3 && $y == 2) {
            for ($i = 0; $i < 5; $i++) {
                $adjacentTiles[] = '' . ($level + 1) . ',4,' . $i;
            }
        } elseif ($x == 4) {
            $adjacentTiles[] = '' . ($level - 1) . ',3,2';
        }

        if ($y == 0) {
            $adjacentTiles[] = '' . ($level - 1) . ',2,1';
        } elseif ($y == 1 && $x == 2) {
            for ($i = 0; $i < 5; $i++) {
                $adjacentTiles[] = '' . ($level + 1) . ',' . $i . ',0';
            }
        } elseif ($y == 3 && $x == 2) {
            for ($i = 0; $i < 5; $i++) {
                $adjacentTiles[] = '' . ($level + 1) . ',' . $i . ',4';
            }
        } elseif ($y == 4) {
            $adjacentTiles[] = '' . ($level - 1) . ',2,3';
        }

        return $adjacentTiles;
    }
}
