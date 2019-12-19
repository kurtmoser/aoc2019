<?php

namespace Aoc2019\Day13;

require __DIR__ . '/../../vendor/autoload.php';

$programString = trim(file_get_contents('input-day13.txt'));

// Part 1

$computer = new IntCodeComputer($programString);

$numBlockTiles = 0;

while (true) {
    // Get x coordinate
    $opCode = $computer->runUntilOutput();

    if ($opCode === 99) {
        break;
    }

    // Get y coordinate
    $computer->runUntilOutput();

    // Get tile id
    $computer->runUntilOutput();

    if ($computer->getOutputArray()[count($computer->getOutputArray()) - 1] === 2) {
        $numBlockTiles++;
    }
}

echo $numBlockTiles . PHP_EOL;

// Part 2

$computer = new IntCodeComputer('2' . substr($programString, 1));

$gameBoard = null;

$ballX = null;
$ballPreviousX = null;
$paddleX = null;

while (true) {
    $opCode = $computer->pauseBeforeOp([3, 4]);

    if ($opCode === 99) {
        break;
    }

    if ($computer->getNextOp() === 3) {
        if ($ballPreviousX) {
            $ballVectorX = $ballX - $ballPreviousX;

            if ($ballX === ($paddleX + $ballVectorX)) {
                $computer->putInput($ballVectorX);
            } else {
                $computer->putInput(0);
            }
        }
        $computer->runOp();
    } elseif ($computer->getNextOp() === 4) {
        $computer->runOp();
        $tileX = $computer->getOutputArray()[count($computer->getOutputArray()) - 1];

        $computer->runOp();
        $tileY = $computer->getOutputArray()[count($computer->getOutputArray()) - 1];

        $computer->runOp();
        $tileId = $computer->getOutputArray()[count($computer->getOutputArray()) - 1];

        if ($tileX === -1 && $tileY === 0) {
            // Score update
        } else {
            $gameBoard[$tileX][$tileY] = $tileId;

            if ($tileId === 4) {
                $ballPreviousX = $ballX;
                $ballX = $tileX;
            } elseif ($tileId === 3) {
                $paddleX = $tileX;
            }
        }
    }
}

echo $computer->getOutputArray()[count($computer->getOutputArray()) - 1] . PHP_EOL;
