<?php

namespace Aoc2019\Day20;

require __DIR__ . '/../../vendor/autoload.php';

$inputMap = file_get_contents(__DIR__ . '/input-day20.txt');

$pathSeeker = new PathSeeker;
$pathSeeker->loadMap($inputMap);

// Part 1

echo $pathSeeker->findMinPathOnSingleMap() . PHP_EOL;

// Part 2

echo $pathSeeker->findMinPathOnRecursiveMap() . PHP_EOL;
