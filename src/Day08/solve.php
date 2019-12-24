<?php

namespace Aoc2019\Day08;

require __DIR__ . '/../../vendor/autoload.php';

$inputData = trim(file_get_contents('input-day08.txt'));

$spaceImage = new SpaceImage(25, 6);

$spaceImage->setData($inputData);

// Part 1

$minLayer = -1;
$minZeroDigits = PHP_INT_MAX;

for ($i = 0; $i < $spaceImage->countLayers(); $i++) {
    $zeroDigits = $spaceImage->countDigitsInLayer($i, 0);

    if ($zeroDigits < $minZeroDigits) {
        $minZeroDigits = $zeroDigits;
        $minLayer = $i;
    }
}

echo $spaceImage->countDigitsInLayer($minLayer, 1) * $spaceImage->countDigitsInLayer($minLayer, 2) . PHP_EOL;

// Part 2

$spaceImage->printFlattened();
