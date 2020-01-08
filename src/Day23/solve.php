<?php

namespace Aoc2019\Day23;

use Aoc2019\Day13\IntCodeComputer;

require __DIR__ . '/../../vendor/autoload.php';

$programString = file_get_contents(__DIR__ . '/input-day23.txt');

// Part 1

$networkInterface = new NetworkInterfaceController;
$networkInterface->loadProgram($programString);
echo $networkInterface->getFirstNatPackage()['y'] . PHP_EOL;

// Part 2

$networkInterface = new NetworkInterfaceController;
$networkInterface->loadProgram($programString);
echo $networkInterface->getFirstResentNatPackage()['y'] . PHP_EOL;
