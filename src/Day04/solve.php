<?php

namespace Aoc2019\Day04;

require __DIR__ . '/../../vendor/autoload.php';

$minPassword = 278384;
$maxPassword = 824795;

$firstCriteriaPasswords = 0;
$secondCriteriaPasswords = 0;

for ($password = $minPassword; $password <= $maxPassword; $password++) {
    if (PasswordValidator::matchesFirstCriteria($password)) {
        $firstCriteriaPasswords++;

        // Only passwords matching first criteria can also match second criteria
        if (PasswordValidator::matchesSecondCriteria($password)) {
            $secondCriteriaPasswords++;
        }
    }
}

// Part 1

echo $firstCriteriaPasswords . PHP_EOL;

// Part 2

echo $secondCriteriaPasswords . PHP_EOL;
