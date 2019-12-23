<?php

namespace Aoc2019\Day06;

require __DIR__ . '/../../vendor/autoload.php';

$orbits = explode("\n", trim(file_get_contents('input-day06.txt')));

$orbitMap = new OrbitMap;

foreach ($orbits as $orbit) {
    [$parent, $child] = explode(')', $orbit);

    $orbitMap->addOrbit($parent, $child);
}

// Part 1

echo $orbitMap->getCumulativeOrbits() . PHP_EOL;

// Part 2

echo $orbitMap->getOrbitalTransfers('YOU', 'SAN') . PHP_EOL;
