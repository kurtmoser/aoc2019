<?php

namespace Aoc2019\Day03;

require __DIR__ . '/../../vendor/autoload.php';

$wireStrings = explode("\n", trim(file_get_contents('input-day03.txt')));

// Part 1

echo WireSolver::getMinDistanceCrossing($wireStrings[0], $wireStrings[1]) . PHP_EOL;

// Part 2

echo WireSolver::getMinStepsCrossing($wireStrings[0], $wireStrings[1]) . PHP_EOL;
