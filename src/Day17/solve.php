<?php

namespace Aoc2019\Day17;

require __DIR__ . '/../../vendor/autoload.php';

$programString = trim(file_get_contents(__DIR__ . '/input-day17.txt'));

$scaffoldRunner = new ScaffoldRunner;

$scaffoldRunner->loadProgram($programString);
$scaffoldRunner->buildMap();

// Part 1

echo $scaffoldRunner->getCrossingCoordinatesSum() . PHP_EOL;

// Part 2

echo $scaffoldRunner->getCollectedDustAmount() . PHP_EOL;
