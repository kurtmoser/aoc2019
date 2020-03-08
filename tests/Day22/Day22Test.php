<?php

namespace Aoc2019\Day22;

use PHPUnit\Framework\TestCase;

class Day22Test extends TestCase
{
    /** @test */
    public function deckCalculatesPositionOfCard()
    {
        $deck = new Deck(10);

        $deck->loadShuffleRules(file_get_contents(__DIR__ . '/input-test.txt'));

        // Before:  0  1  2  3  4  5  6  7  8  9
        // After:   9  2  5  8  1  4  7  0  3  6

        $this->assertEquals(7, $deck->getPositionOfCard(0));
        $this->assertEquals(4, $deck->getPositionOfCard(1));
        $this->assertEquals(0, $deck->getPositionOfCard(9));
    }

    /** @test */
    public function deckCalculatesCardAtPosition()
    {
        $deck = new Deck(10);

        $deck->loadShuffleRules(file_get_contents(__DIR__ . '/input-test.txt'));

        $this->assertEquals(9, $deck->getCardAtPosition(0));
        $this->assertEquals(1, $deck->getCardAtPosition(4));
        $this->assertEquals(0, $deck->getCardAtPosition(7));
    }
}
