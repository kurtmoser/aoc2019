<?php

namespace Aoc2019\Day18;

class KeyCollector
{
    public $map;

    public $objectCoords;

    public $keyDistances;

    public $totalKeys;

    public $memoization;


    public function loadMap($mapString)
    {
        $inputLines = explode("\n", $mapString);

        for ($y = 0; $y < count($inputLines); $y++) {
            for ($x = 0; $x < strlen($inputLines[$y]); $x++) {
                $this->map[$y][$x] = $inputLines[$y][$x];
            }
        }

        // Cleaning map of unrelated doors is obsolete with "correct" input map
        // but it is needed for part 2 of the challenge where submaps may
        // contain doors for which the keys are located in other submaps.
        $this->cleanMap();

        $this->readObjectCoords();

        $this->calculateKeyDistances();
    }

    public function readObjectCoords()
    {
        $coords = [];

        for ($y = 0; $y < count($this->map); $y++) {
            for ($x = 0; $x < count($this->map[0]); $x++) {
                if (!in_array($this->map[$y][$x], ['#', '.'])) {
                    $coords[$this->map[$y][$x]] = $x . ',' . $y;
                }
            }
        }

        $this->objectCoords = $coords;
    }

    public function calculateKeyDistances()
    {
        $keys = '';

        foreach ($this->objectCoords as $tileId => $tileCoords) {
            if (!preg_match('/[a-z]/', $tileId)) {
                continue;
            }

            $keys .= $tileId;
        }

        $keys .= '@';

        $distances = [];

        for ($i = 0; $i < strlen($keys) - 1; $i++) {
            for ($j = $i + 1; $j < strlen($keys); $j++) {
                [$startX, $startY] = explode(',', $this->objectCoords[$keys[$i]]);
                [$endX, $endY] = explode(',', $this->objectCoords[$keys[$j]]);

                $res = $this->findMinPath(new Point($startX, $startY), new Point($endX, $endY));

                $distances[] = [
                    'dist' => $res['dist'],
                    'doors' => $res['doors'],
                    'keypair' => $keys[$i] . $keys[$j],
                ];
            }
        }

        $this->keyDistances = $distances;

        $this->totalKeys = '@';
        foreach ($this->keyDistances as $key => $val) {
            if (strpos($this->totalKeys, $val['keypair'][0]) === false) {
                $this->totalKeys .= $val['keypair'][0];
            }
        }
    }

    protected function findMinPath($startPoint, $endPoint)
    {
        $xNum = [-1, 0, 0, 1];
        $yNum = [0, -1, 1, 0];

        $visited = [];
        for ($y = 0; $y < count($this->map); $y++) {
            for ($x = 0; $x < count($this->map[0]); $x++) {
                $visited[$y][$x] = false;
            }
        }

        $visited[$startPoint->y][$startPoint->x] = true;

        $queue = [];
        array_push($queue, [
            'point' => $startPoint,
            'dist' => 0,
            'doors' => '',
        ]);

        while (count($queue)) {
            $data = $queue[0];
            if ($data['point']->x == $endPoint->x
                && $data['point']->y == $endPoint->y
            ) {
                return $data;
            }

            array_shift($queue);

            for ($i = 0; $i < 4; $i++) {
                $x = $data['point']->x + $xNum[$i];
                $y = $data['point']->y + $yNum[$i];
                $doors = $data['doors'];

                if ($x < 0 || $x >= count($this->map[0])
                    || $y < 0 || $y >= count($this->map)
                ) {
                    continue;
                }

                if ($visited[$y][$x]) {
                    continue;
                }

                if ($this->map[$y][$x] == '#') {
                    continue;
                }

                if (preg_match('/[A-Z]/', $this->map[$y][$x])) {
                    // continue;
                    $doors .= $this->map[$y][$x];
                }

                $visited[$y][$x] = true;

                array_push($queue, [
                    'point' => new Point($x, $y),
                    'dist' => $data['dist'] + 1,
                    'doors' => $doors,
                ]);
            }
        }

        return -1;
    }

    public function calculateBestMinPath($fromKey, $remainingKeys)
    {
        $memoKey = $fromKey . ':' . $remainingKeys;

        if (isset($this->memoization[$memoKey])) {
            return $this->memoization[$memoKey];
        }

        $calculatedKeys = preg_replace('/[ ' . $remainingKeys . $fromKey . ']/', '', $this->totalKeys);

        $subDistanceInfo = $this->keyDistances;

        if ($remainingKeys == '') {
            $this->memoization[$memoKey] = [
                'dist' => 0,
                'path' => $fromKey,
            ];

            return $this->memoization[$memoKey];
        }

        // Remove non-fromkeys from distance array
        foreach ($subDistanceInfo as $i => $value) {
            if ($subDistanceInfo[$i]['keypair'][0] !== $fromKey
                && $subDistanceInfo[$i]['keypair'][1] !== $fromKey
            ) {
                unset($subDistanceInfo[$i]);
            }
        }
        $subDistanceInfo = array_values($subDistanceInfo);

        // Remove calculated keys from distance array
        foreach ($subDistanceInfo as $i => $value) {
            if (strpos($calculatedKeys, $subDistanceInfo[$i]['keypair'][0]) !== false
                || strpos($calculatedKeys, $subDistanceInfo[$i]['keypair'][1]) !== false
            ) {
                unset($subDistanceInfo[$i]);
            }
        }
        $subDistanceInfo = array_values($subDistanceInfo);

        // Remove calculated doors from doorslist for each distance array element
        $doorsToRemove = strtoupper($calculatedKeys . $fromKey);
        for ($i = 0; $i < count($subDistanceInfo); $i++) {
            $subDistanceInfo[$i]['doors'] = preg_replace('/[ ' . $doorsToRemove . ']/', '', $subDistanceInfo[$i]['doors']);
        }

        // We should have suitable candidates now, pick only keypairs that don't have doors between them
        $bestSubResult = [
            'dist' => PHP_INT_MAX,
            'path' => '?',
        ];
        $bestSubIndex = -1;
        $bestDistanceIncludingFromKey = PHP_INT_MAX;

        for ($i = 0; $i < count($subDistanceInfo); $i++) {
            if ($subDistanceInfo[$i]['doors'] != '') {
                continue;
            }

            $nextFromKey = preg_replace('/' . $fromKey . '/', '', $subDistanceInfo[$i]['keypair']);
            $nextRemainingKeys = preg_replace('/' . $nextFromKey . '/', '', $remainingKeys);

            $subResult = $this->calculateBestMinPath($nextFromKey, $nextRemainingKeys);

            // Take into account distance between fromkey and nextfromkey
            $distanceIncludingFromKey = $subResult['dist'] + $subDistanceInfo[$i]['dist'];

            if ($distanceIncludingFromKey < $bestDistanceIncludingFromKey) {
                $bestSubResult = $subResult;
                $bestSubIndex = $i;
                $bestDistanceIncludingFromKey = $distanceIncludingFromKey;
            }
        }

        $this->memoization[$memoKey] = [
            'dist' => $bestSubResult['dist'] + $subDistanceInfo[$bestSubIndex]['dist'],
            'path' => $fromKey . $bestSubResult['path'],
        ];

        return $this->memoization[$memoKey];
    }

    public function calculateMinSteps($startingPoint)
    {
        $allKeys = '@';

        foreach ($this->keyDistances as $key => $val) {
            if (strpos($allKeys, $val['keypair'][0]) === false) {
                $allKeys .= $val['keypair'][0];
            }
        }

        $res = $this->calculateBestMinPath($startingPoint, str_replace($startingPoint, '', $allKeys));
        return $res['dist'];
    }

    /**
     * Remove unrelated doors from map
     */
    public function cleanMap()
    {
        $keys = '';
        for ($y = 0; $y < count($this->map); $y++) {
            for ($x = 0; $x < count($this->map[0]); $x++) {
                if (preg_match('/[a-z]/', $this->map[$y][$x])) {
                    $keys .= $this->map[$y][$x];
                }
            }
        }

        $supportedDoors = strtoupper($keys);
        for ($y = 0; $y < count($this->map); $y++) {
            for ($x = 0; $x < count($this->map[0]); $x++) {
                if (preg_match('/[A-Z]/', $this->map[$y][$x])) {
                    if (strpos($supportedDoors, $this->map[$y][$x]) === false) {
                        $this->map[$y][$x] = '.';
                    }
                }
            }
        }
    }
}
