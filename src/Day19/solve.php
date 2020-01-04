<?php

namespace Aoc2019\Day19;

use Aoc2019\Day13\IntCodeComputer;

require __DIR__ . '/../../vendor/autoload.php';

$programString = trim(file_get_contents(__DIR__ . '/input-day19.txt'));

$beamAnalyzer = new BeamAnalyzer;
$beamAnalyzer->setProgram($programString);

// Part 1

echo $beamAnalyzer->countAffectedPositions(0, 0, 49, 49) . PHP_EOL;

// Part 2

echo $beamAnalyzer->getMinCoordinatesToFitObject(100, 100) . PHP_EOL;
