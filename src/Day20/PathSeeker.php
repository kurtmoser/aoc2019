<?php

namespace Aoc2019\Day20;

class PathSeeker
{
    protected $map;

    protected $portals;

    protected $startPoint;

    protected $endPoint;

    public function loadMap($mapString)
    {
        $mapLines = explode("\n", $mapString);

        for ($y = 0; $y < count($mapLines); $y++) {
            if (strlen($mapLines[$y]) == 0) {
                continue;
            }

            for ($x = 0; $x < strlen($mapLines[$y]); $x++) {
                $this->map[$y][$x] = $mapLines[$y][$x];
            }
        }

        $this->detectPortals();
    }

    protected function detectPortals()
    {
        $tmpBuffer = [];

        for ($y = 1; $y < count($this->map) - 1; $y++) {
            for ($x = 1; $x < count($this->map[0]) - 1; $x++) {
                if (preg_match('/[A-Z]/', $this->map[$y][$x])) {
                    $portalName = '';
                    $inX = $x;
                    $inY = $y;
                    $outX = -1;
                    $outY = -1;
                    $inner = -1;

                    if ($this->map[$y][$x + 1] == '.') {
                        $portalName = $this->map[$y][$x - 1] . $this->map[$y][$x];
                        $outX = $x + 1;
                        $outY = $y;

                        if ($x < (int)(count($this->map[0]) / 2)) {
                            $inner = 0;
                        } else {
                            $inner = 1;
                        }
                    } elseif ($this->map[$y][$x - 1] == '.') {
                        $portalName = $this->map[$y][$x] . $this->map[$y][$x + 1];
                        $outX = $x - 1;
                        $outY = $y;

                        if ($x < (int)(count($this->map[0]) / 2)) {
                            $inner = 1;
                        } else {
                            $inner = 0;
                        }
                    } elseif ($this->map[$y - 1][$x] == '.') {     // TODO: replece with bottom elseif so x and y logics align
                        $portalName = $this->map[$y][$x] . $this->map[$y + 1][$x];
                        $outX = $x;
                        $outY = $y - 1;

                        if ($y < (int)(count($this->map) / 2)) {
                            $inner = 1;
                        } else {
                            $inner = 0;
                        }
                    } elseif ($this->map[$y + 1][$x] == '.') {
                        $portalName = $this->map[$y - 1][$x] . $this->map[$y][$x];
                        $outX = $x;
                        $outY = $y + 1;

                        if ($y < (int)(count($this->map) / 2)) {
                            $inner = 0;
                        } else {
                            $inner = 1;
                        }
                    }

                    if ($portalName) {
                        $tmpBuffer[$portalName][] = [
                            'in' => $inX . ',' . $inY,
                            'out' => $outX . ',' . $outY,
                            'inner' => $inner,
                        ];
                    }
                }
            }
        }

        foreach ($tmpBuffer as $key => $value) {
            if ($key == 'AA') {
                [$startX, $startY] = explode(',', $value[0]['out']);
                $this->startPoint = new Point($startX, $startY);
            } elseif ($key == 'ZZ') {
                [$endX, $endY] = explode(',', $value[0]['out']);
                $this->endPoint = new Point($endX, $endY);
            } else {
                $portals[$value[0]['in']] = [
                    'out' => $value[1]['out'],
                    'block' => $value[1]['in'],
                    'level_change' => $value[0]['inner'] ? 1 : -1,
                ];
                $portals[$value[1]['in']] = [
                    'out' => $value[0]['out'],
                    'block' => $value[0]['in'],
                    'level_change' => $value[1]['inner'] ? 1 : -1,
                ];
            }
        }

        $this->portals = $portals;
    }

    protected function findMinPath($recursively = false)
    {
        $xNum = [-1, 0, 0, 1];
        $yNum = [0, -1, 1, 0];

        $visited = [];
        for ($level = 0; $level < 100; $level++) {
            for ($y = 0; $y < count($this->map); $y++) {
                for ($x = 0; $x < count($this->map[0]); $x++) {
                    $visited[$level][$y][$x] = false;
                }
            }
        }

        $visited[0][$this->startPoint->y][$this->startPoint->x] = true;

        $queue = [];
        array_push($queue, [
            'point' => $this->startPoint,
            'dist' => 0,
        ]);

        while (count($queue)) {
            $data = $queue[0];
            if ($data['point']->x == $this->endPoint->x
                && $data['point']->y == $this->endPoint->y
                && $data['point']->z == 0
            ) {
                return $data;
            }

            array_shift($queue);

            for ($i = 0; $i < 4; $i++) {
                $x = $data['point']->x + $xNum[$i];
                $y = $data['point']->y + $yNum[$i];
                $level = $data['point']->z;

                if ($x < 0 || $x >= count($this->map[0])
                    || $y < 0 || $y >= count($this->map)
                ) {
                    continue;
                }

                if ($visited[$level][$y][$x]) {
                    continue;
                }

                if (preg_match('/[A-Z]/', $this->map[$y][$x])) {
                    if (isset($this->portals[$x . ',' . $y])) {
                        [$outX, $outY] = explode(',', $this->portals[$x . ',' . $y]['out']);
                        [$blockX, $blockY] = explode(',', $this->portals[$x . ',' . $y]['block']);

                        $outLevel = $level;
                        if ($recursively) {
                            $outLevel += $this->portals[$x . ',' . $y]['level_change'];
                        }

                        $visited[$outLevel][$outY][$outX] = true;
                        $visited[$outLevel][$blockY][$blockX] = true;

                        if ($outLevel < 0) {
                            continue;
                        }
                        if ($outLevel >= 100) {
                            continue;
                        }

                        array_push($queue, [
                            'point' => new Point($outX, $outY, $outLevel),
                            'dist' => $data['dist'] + 1,
                        ]);
                    }

                    continue;
                }

                if ($this->map[$y][$x] != '.') {
                    continue;
                }

                $visited[$level][$y][$x] = true;
                array_push($queue, [
                    'point' => new Point($x, $y, $level),
                    'dist' => $data['dist'] + 1,
                ]);
            }
        }

        return -1;
    }

    public function findMinPathOnSingleMap()
    {
        return $this->findMinPath()['dist'];
    }

    public function findMinPathOnRecursiveMap()
    {
        return $this->findMinPath(true)['dist'];
    }
}
