<?php

namespace Aoc2019\Day09;

require __DIR__ . '/../../vendor/autoload.php';

$programString = trim(file_get_contents('input-day09.txt'));

// Part 1

$computer = new IntCodeComputer($programString);
$computer->putInput(1);
$computer->run();
echo $computer->getOutputArray()[0] . PHP_EOL;

// Part 2

$computer = new IntCodeComputer($programString);
$computer->putInput(2);
$computer->run();
echo $computer->getOutputArray()[0] . PHP_EOL;
