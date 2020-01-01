<?php

namespace Aoc2019\Day18;

require __DIR__ . '/../../vendor/autoload.php';

$inputMap = trim(file_get_contents('input-day18.txt'));

// Part 1

$keyCollector = new KeyCollector;
$keyCollector->loadMap($inputMap);

echo $keyCollector->calculateMinSteps('@') . PHP_EOL;

// Part 2

// NB! This is non-complete solution that luckily works with challenge input.
// From each submap we remove doors for which the keys dont exist in that
// same submap. Then we just solve each submap separately and sum these results.

$totalSteps = 0;

$mapLines = explode("\n", $inputMap);
$submapWidth = ceil(strlen($mapLines[0]) / 2);
$submapHeight = ceil(count($mapLines) / 2);

// Top left submap
$submapLines = [];
for ($y = 0; $y < $submapHeight; $y++) {
    $submapLines[] = substr($mapLines[$y], 0, $submapWidth);
}
$submapLines[count($submapLines) - 2] = substr($submapLines[count($submapLines) - 2], 0, -2) . '@#';
$submapLines[count($submapLines) - 1] = substr($submapLines[count($submapLines) - 1], 0, -2) . '##';
$keyCollector = new KeyCollector;
$keyCollector->loadMap(implode(PHP_EOL, $submapLines));
$totalSteps += $keyCollector->calculateMinSteps('@');

// Bottom left submap
$submapLines = [];
for ($y = $submapHeight - 1; $y < ($submapHeight - 1) + $submapHeight; $y++) {
    $submapLines[] = substr($mapLines[$y], 0, $submapWidth);
}
$submapLines[0] = substr($submapLines[0], 0, -2) . '##';
$submapLines[1] = substr($submapLines[1], 0, -2) . '@#';
$submap = implode(PHP_EOL, $submapLines);
$keyCollector = new KeyCollector;
$keyCollector->loadMap($submap);
$totalSteps += $keyCollector->calculateMinSteps('@');

// Top right submap
$submapLines = [];
for ($y = 0; $y < $submapHeight; $y++) {
    $submapLines[] = substr($mapLines[$y], $submapWidth - 1, $submapWidth);
}
$submapLines[count($submapLines) - 2] = '#@' . substr($submapLines[count($submapLines) - 2], 2);
$submapLines[count($submapLines) - 1] = '##' . substr($submapLines[count($submapLines) - 1], 2);
$submap = implode(PHP_EOL, $submapLines);
$keyCollector = new KeyCollector;
$keyCollector->loadMap($submap);
$totalSteps += $keyCollector->calculateMinSteps('@');

// Bottom right submap
$submapLines = [];
for ($y = $submapHeight - 1; $y < ($submapHeight - 1) + $submapHeight; $y++) {
    $submapLines[] = substr($mapLines[$y], $submapWidth - 1, $submapWidth);
}
$submapLines[0] = '##' . substr($submapLines[0], 2);
$submapLines[1] = '#@' . substr($submapLines[1], 2);
$submap = implode(PHP_EOL, $submapLines);
$keyCollector = new KeyCollector;
$keyCollector->loadMap($submap);
$totalSteps += $keyCollector->calculateMinSteps('@');

echo $totalSteps . PHP_EOL;
