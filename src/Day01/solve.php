<?php

namespace Aoc2019\Day01;

require __DIR__ . '/../../vendor/autoload.php';

$solver = new Day01Solver;

$moduleMasses = explode("\n", trim(file_get_contents('input.txt')));

echo 'required fuel: ' . $solver->calculateFuel($moduleMasses) . PHP_EOL;
echo 'recursively required fuel: ' . $solver->calculateRecursiveFuel($moduleMasses) . PHP_EOL;
