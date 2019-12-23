<?php

namespace Aoc2019\Day06;

class OrbitMap
{
    public $orbits = [];

    public function addOrbit($parent, $child)
    {
        if (!isset($this->orbits[$parent])) {
            $this->orbits[$parent] = [
                'parent' => null,
                'children' => [],
            ];
        }

        if (!isset($this->orbits[$child])) {
            $this->orbits[$child] = [
                'parent' => null,
                'children' => [],
            ];
        }

        $this->orbits[$parent]['children'][] = $child;
        $this->orbits[$child]['parent'] = $parent;
    }

    public function getCumulativeOrbits()
    {
        $cumulativeOrbits = 0;

        foreach ($this->orbits as $parent => $children) {
            $runner = $this->orbits[$parent];

            while ($runner['parent']) {
                $runner = $this->orbits[$runner['parent']];
                $cumulativeOrbits++;
            }
        }

        return $cumulativeOrbits;
    }

    public function getPathToRoot($object)
    {
        $path = [$object];

        $runner = $this->orbits[$object];

        while ($runner['parent']) {
            array_unshift($path, $runner['parent']);

            $runner = $this->orbits[$runner['parent']];
        }

        return $path;
    }

    public function getOrbitalTransfers($object1, $object2)
    {
        $path1 = $this->getPathToRoot($object1);
        $path2 = $this->getPathToRoot($object2);

        while ($path1[0] == $path2[0]) {
            array_shift($path1);
            array_shift($path2);
        }

        return count($path1) + count($path2) - 2;
    }
}
