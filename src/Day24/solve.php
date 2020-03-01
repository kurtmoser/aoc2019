<?php

namespace Aoc2019\Day24;

require __DIR__ . '/../../vendor/autoload.php';

$inputMap = file_get_contents(__DIR__ . '/input-day24.txt');

$biodiversityTracker = new BiodiversityTracker();

// Part 1

$biodiversityTracker->loadMap($inputMap);
echo $biodiversityTracker->findFirstRepeatingVariation() . PHP_EOL;

// Part 2

$biodiversityTracker->loadMap($inputMap);
echo $biodiversityTracker->countBugsAfterEvolution(200) . PHP_EOL;
