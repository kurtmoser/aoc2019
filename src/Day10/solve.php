<?php

namespace Aoc2019\Day10;

require __DIR__ . '/../../vendor/autoload.php';

$inputString = trim(file_get_contents('input-day10.txt'));

$monitoringStation = new MontioringStation;
$monitoringStation->loadMap($inputString);
$monitoringStation->calculateAsteroidDistances();

// Part 1

$baseAsteroid = $monitoringStation->getAsteroidWithMostVisibleNeighbors();
echo count($baseAsteroid['direction_containing_asteroids']) . PHP_EOL;

// Part 2

echo $monitoringStation->findNthAsteroidCoordinates($baseAsteroid, 200) . PHP_EOL;
