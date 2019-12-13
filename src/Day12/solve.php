<?php

namespace Aoc2019\Day12;

require __DIR__ . '/../../vendor/autoload.php';

$inputData = trim(file_get_contents('input-day12.txt'));

// Part 1

$gravitySimulator = new GravitySimulator();

foreach (explode("\n", $inputData) as $inputLine) {
    $res = preg_match('/x=(-?\d+), y=(-?\d+), z=(-?\d+)/', $inputLine, $matches);

    if ($res) {
        $gravitySimulator->addObject(new GravityObject([$matches[1], $matches[2], $matches[3]]));
    }
}

for ($i = 0; $i < 1000; $i++) {
    $gravitySimulator->recalculateVelocities();
    $gravitySimulator->recalculatePositions();
}

echo $gravitySimulator->calculateTotalEnergy() . PHP_EOL;

// Part 2

// Calculate each axis' orbit length separately. Find out how many steps it
// takes for the system to return to its initial state on specific axis.
// When orbit lengths for each separate axis have been calculated, use lcm
// (least common multiplier) to find the point when all axes return to their
// initial state together.

$gravitySimulator = new GravitySimulator();

foreach (explode("\n", $inputData) as $inputLine) {
    $res = preg_match('/x=(-?\d+), y=(-?\d+), z=(-?\d+)/', $inputLine, $matches);

    if ($res) {
        $gravitySimulator->addObject(new GravityObject([$matches[1], $matches[2], $matches[3]]));
    }
}

$initialState = [];
foreach ($gravitySimulator->getObjects() as $object) {
    $initialState[] = clone $object;
}

$axisOrbitLengths = [];

for ($axis = 0; $axis < 3; $axis++) {
    $steps = 0;

    while (true) {
        $steps++;

        $gravitySimulator->recalculateAxisVelocities($axis);
        $gravitySimulator->recalculateAxisPositions($axis);

        $identicalState = true;
        for ($i = 0; $i < count($initialState); $i++) {
            if ($gravitySimulator->getObject($i)->getPosition($axis) != $initialState[$i]->getPosition($axis)
                || $gravitySimulator->getObject($i)->getVelocity($axis) != 0
            ) {
                $identicalState = false;
                break;
            }
        }

        if ($identicalState) {
            break;
        }
    }

    $axisOrbitLengths[$axis] = $steps;
}

echo gmp_lcm($axisOrbitLengths[0], gmp_lcm($axisOrbitLengths[1], $axisOrbitLengths[2])) . PHP_EOL;
