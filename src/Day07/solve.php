<?php

namespace Aoc2019\Day07;

require __DIR__ . '/../../vendor/autoload.php';

$sequences = [];

for ($a = 0; $a < 5; $a++) {
    for ($b = 0; $b < 5; $b++) {
        for ($c = 0; $c < 5; $c++) {
            for ($d = 0; $d < 5; $d++) {
                for ($e = 0; $e < 5; $e++) {
                    if (preg_match('/(.).*\1/', $a . $b . $c . $d . $e)) {
                        continue;
                    }

                    $sequences[] = [$a, $b, $c, $d, $e];
                }
            }
        }
    }
}

$inputProgram = trim(file_get_contents('input-day07.txt'));

// Part 1

$maxThrust = 0;

foreach ($sequences as $sequence) {
    $amplificationCircuit = new AmplificationCircuit($inputProgram);
    $amplificationCircuit->setSequence($sequence);
    $thrust = $amplificationCircuit->getThrust();

    if ($thrust > $maxThrust) {
        $maxThrust = $thrust;
    }
}

echo $maxThrust . PHP_EOL;

// Part 2

for ($i = 0; $i < count($sequences); $i++) {
    for ($j = 0; $j < 5; $j++) {
        $sequences[$i][$j] += 5;
    }
}

$maxThrustInLoopMode = 0;

foreach ($sequences as $sequence) {
    $amplificationCircuit = new AmplificationCircuit($inputProgram);
    $amplificationCircuit->setSequence($sequence);
    $thrust = $amplificationCircuit->getThrustInLoopMode();

    if ($thrust > $maxThrustInLoopMode) {
        $maxThrustInLoopMode = $thrust;
    }
}

echo $maxThrustInLoopMode . PHP_EOL;
