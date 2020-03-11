<?php

namespace Aoc2019\Day25;

require __DIR__ . '/../../vendor/autoload.php';

$shipScanner = new ShipScanner(trim(file_get_contents(__DIR__ . '/input-day25.txt')));

echo $shipScanner->findSecurityCode() . PHP_EOL;
