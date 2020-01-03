<?php

namespace Aoc2019\Day15;

require __DIR__ . '/../../vendor/autoload.php';

$programString = trim(file_get_contents('input-day15.txt'));

$oxygenSystem = new OxygenSystem;

$oxygenSystem->loadProgram($programString);
$oxygenSystem->buildMap();

// Part 1

echo $oxygenSystem->findMinPathToOxygen() . PHP_EOL;

// Part 2

echo $oxygenSystem->findTimeToFillMapWithOxygen() . PHP_EOL;
