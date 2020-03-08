<?php

namespace Aoc2019\Day22;

require __DIR__ . '/../../vendor/autoload.php';

// NOTE: Part 2 was not solved independently. It requires good grasp of concepts
// like modular arithmetics that I don't posess. Thus this part was constructed
// from other people's solutions + tips from reddit thread.
// Once part 2 was complete, part 1 was also improved to support large decks
// (numCards must be prime) and shuffle repeats.

$inputData = file_get_contents(__DIR__ . '/input-day22.txt');

// Part 1

$deck = new Deck(10007);
$deck->loadShuffleRules($inputData);
echo $deck->getPositionOfCard(2019) . PHP_EOL;
// echo $deck->getCardAtPosition(6978) . PHP_EOL;

// Part 2

$deck = new Deck(119315717514047);
$deck->loadShuffleRules($inputData);
echo $deck->getCardAtPosition(2020, 101741582076661) . PHP_EOL;
// echo $deck->getPositionOfCard(24460989449140, 101741582076661) . PHP_EOL;
