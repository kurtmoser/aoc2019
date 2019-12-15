<?php

namespace Aoc2019\Day11;

use Aoc2019\Day09\IntCodeComputer;

require __DIR__ . '/../../vendor/autoload.php';

$programString = trim(file_get_contents('input-day11.txt'));

$directionSpeeds = [
    [0, 1],
    [1, 0],
    [0, -1],
    [-1, 0],
];

// Part 1

$computer = new IntCodeComputer($programString);

$paintedPanels = [];

$robotPosition = [0, 0];

$robotDirection = 0;

while (true) {
    // Feed color of the tile we are currently on as input
    $computer->putInput($paintedPanels[$robotPosition[0] . ',' . $robotPosition[1]] ?? 0);

    // Calculate what color robot wants to paint the tile
    $opCode = $computer->runUntilOutput();

    if ($opCode === 99) {
        break;
    }

    $paintColor = $computer->getOutputArray()[count($computer->getOutputArray()) - 1];
    $paintedPanels[$robotPosition[0] . ',' . $robotPosition[1]] = $paintColor;

    // Calculate which way robot wants to turn
    $computer->runUntilOutput();

    $moveDirection = $computer->getOutputArray()[count($computer->getOutputArray()) - 1];
    if ($moveDirection === 0) {
        $robotDirection = ($robotDirection - 1 + 4) % 4;
    } elseif ($moveDirection === 1) {
        $robotDirection = ($robotDirection + 1) % 4;
    }

    // Move robot
    $robotPosition[0] += $directionSpeeds[$robotDirection][0];
    $robotPosition[1] += $directionSpeeds[$robotDirection][1];
}

echo count($paintedPanels) . PHP_EOL;

// Part 2

$computer = new IntCodeComputer($programString);

$paintedPanels = ['0,0' => 1];

$robotPosition = [0, 0];

$robotDirection = 0;

$minX = 0;
$minY = 0;
$maxX = 0;
$maxY = 0;

while (true) {
    // Feed color of the tile we are currently on as input
    $computer->putInput($paintedPanels[$robotPosition[0] . ',' . $robotPosition[1]] ?? 0);

    // Calculate what color robot wants to paint the tile
    $opCode = $computer->runUntilOutput();

    if ($opCode === 99) {
        break;
    }

    $paintColor = $computer->getOutputArray()[count($computer->getOutputArray()) - 1];
    $paintedPanels[$robotPosition[0] . ',' . $robotPosition[1]] = $paintColor;

    // Calculate which way robot wants to turn
    $computer->runUntilOutput();

    $moveDirection = $computer->getOutputArray()[count($computer->getOutputArray()) - 1];
    if ($moveDirection === 0) {
        $robotDirection = ($robotDirection - 1 + 4) % 4;
    } elseif ($moveDirection === 1) {
        $robotDirection = ($robotDirection + 1) % 4;
    }

    // Move robot
    $robotPosition[0] += $directionSpeeds[$robotDirection][0];
    $robotPosition[1] += $directionSpeeds[$robotDirection][1];

    $minX = min($minX, $robotPosition[0]);
    $maxX = max($maxX, $robotPosition[0]);
    $minY = min($minY, $robotPosition[1]);
    $maxY = max($maxY, $robotPosition[1]);
}

echo PHP_EOL;

for ($y = $maxY; $y >= $minY; $y--) {
    for ($x = $minX; $x <= $maxX; $x++) {
        if (isset($paintedPanels[$x . ',' . $y]) && $paintedPanels[$x . ',' . $y] == 1) {
            echo 'X';
        } else {
            echo ' ';
        }
    }

    echo PHP_EOL;
}
