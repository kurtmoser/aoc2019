<?php

namespace Aoc2019\Day05;

require __DIR__ . '/../../vendor/autoload.php';

// Part 1

$computer = new IntCodeComputer;
$programString = file_get_contents('input-day05.txt');

$computer->setProgram($programString);
$computer->setPointer(0);
$computer->putInput(1);
$computer->run();

$programOutput = $computer->getOutputArray();

for ($i = 0; $i < count($programOutput) - 1; $i++) {
    if ($programOutput[$i] !== 0) {
        throw new \Exception('Unexpected program output');
    }
}
echo $programOutput[count($programOutput) - 1] . PHP_EOL;

// Part 2

$computer = new IntCodeComputer;
$programString = file_get_contents('input-day05.txt');

$computer->setProgram($programString);
$computer->setPointer(0);
$computer->putInput(5);
$computer->run();

echo $computer->getOutputArray()[0] . PHP_EOL;
