<?php

namespace Aoc2019\Day14;

require __DIR__ . '/../../vendor/autoload.php';

$reactionString = trim(file_get_contents('input-day14.txt'));

$nanoFactory = new NanoFactory($reactionString);

// Part 1

echo $nanoFactory->dissolveToOre('FUEL') . PHP_EOL;

// Part 2

echo $nanoFactory->produceFromOreAmount(1000000000000, 'FUEL') . PHP_EOL;
