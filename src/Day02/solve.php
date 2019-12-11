<?php

namespace Aoc2019\Day02;

require __DIR__ . '/../../vendor/autoload.php';

$computer = new IntCodeComputer;
$programString = file_get_contents('input-day02.txt');

// Part 1

$computer->setProgram($programString);
$computer->setPointer(0);
$computer->writeToAddress(1, 12);
$computer->writeToAddress(2, 2);
$computer->run();

echo $computer->readFromAddress(0) . PHP_EOL;

// Part 2

for ($noun = 0; $noun < 100; $noun++) {
    for ($verb = 0; $verb < 100; $verb++) {
        $computer->setProgram($programString);
        $computer->setPointer(0);
        $computer->writeToAddress(1, $noun);
        $computer->writeToAddress(2, $verb);
        $computer->run();

        if ($computer->readFromAddress(0) === 19690720) {
            break 2;
        }
    }
}

echo sprintf('%02d', $noun) . sprintf('%02d', $verb) . PHP_EOL;
