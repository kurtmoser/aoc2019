<?php

namespace Aoc2019\Day21;

use Aoc2019\Day13\IntCodeComputer;

require __DIR__ . '/../../vendor/autoload.php';

$programString = file_get_contents(__DIR__ . '/input-day21.txt');

// Part 1

$computer = new IntCodeComputer($programString);

$input = [
    'OR A J',
    'AND B J',
    'AND C J',
    'NOT J J',
    'AND D J',

    'WALK',
];

for ($i = 0; $i < count($input); $i++) {
    for ($j = 0; $j < strlen($input[$i]); $j++) {
        $computer->putInput(ord($input[$i][$j]));
    }
    $computer->putInput(ord("\n"));
}

$computer->run();

echo $computer->getOutputArray()[count($computer->getOutputArray()) - 1] . PHP_EOL;

// Part 2

$computer = new IntCodeComputer($programString);

$input = [
    'OR A J',
    'AND B J',
    'AND C J',
    'NOT J J',
    'AND D J',

    'OR E T',
    'OR H T',
    'AND T J',

    'RUN',
];

for ($i = 0; $i < count($input); $i++) {
    for ($j = 0; $j < strlen($input[$i]); $j++) {
        $computer->putInput(ord($input[$i][$j]));
    }
    $computer->putInput(ord("\n"));
}

$computer->run();

echo $computer->getOutputArray()[count($computer->getOutputArray()) - 1] . PHP_EOL;
