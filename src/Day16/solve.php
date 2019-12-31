<?php

namespace Aoc2019\Day16;

require __DIR__ . '/../../vendor/autoload.php';

$inputSignal = trim(file_get_contents('input-day16.txt'));

$transmissionCleaner = new TransmissionCleaner;
$transmissionCleaner->setBasePattern([0, 1, 0, -1]);

// Part 1

$signal = $inputSignal;

for ($i = 0; $i < 100; $i++) {
    $signal = $transmissionCleaner->processSignal($signal);
}

echo substr($signal, 0, 8) . PHP_EOL;

// Part 2

$messageOffset = (int)substr($inputSignal, 0, 7);
$realSignal = str_repeat($inputSignal, 10000);
$signalEndingPart = substr($realSignal, $messageOffset);

for ($j = 0; $j < 100; $j++) {
    $signalEndingPart = $transmissionCleaner->processSignalEndingPart($signalEndingPart);
}

echo substr($signalEndingPart, 0, 8) . PHP_EOL;
