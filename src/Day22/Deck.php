<?php

namespace Aoc2019\Day22;

class Deck
{
    protected $numCards;

    protected $rules;

    public function __construct($numCards)
    {
        $this->numCards = $numCards;
    }

    public function loadShuffleRules($inputData)
    {
        $this->rules = explode("\n", trim($inputData));
    }

    public function getPositionOfCard($index, $times = 1)
    {
        $offset = 0;
        $step = 1;

        for ($i = count($this->rules) - 1; $i >= 0; $i--) {
            if (preg_match('/deal into new stack/', $this->rules[$i])) {
                $step = -$step;

                $offset = (int)gmp_mod(
                    $offset + $step,
                    $this->numCards
                );
            } elseif (preg_match('/cut (-?[0-9]+)/', $this->rules[$i], $matches)) {
                $offset = (int)gmp_mod(
                    $offset - $step * $matches[1],
                    $this->numCards
                );
            } elseif (preg_match('/deal with increment (-?[0-9]+)/', $this->rules[$i], $matches)) {
                $step = (int)gmp_mod(
                    $step * $matches[1],
                    $this->numCards
                );
            }
        }

        if ($times === 1) {
            return (int)gmp_mod($step * $index + $offset, $this->numCards);
        }

        // Following requires numCards to be prime
        // Check comment at getCardAtPosition

        $res1 = gmp_mul(gmp_powm($step, $times, $this->numCards), $index);

        $res2a = $offset;
        $res2b = gmp_powm($step, $times, $this->numCards) - 1;
        $res2c = gmp_powm($step - 1, $this->numCards - 2, $this->numCards);
        $res2 = gmp_mul($res2a, gmp_mul($res2b, $res2c));

        return (int)gmp_mod($res1 + $res2, $this->numCards);
    }

    public function getCardAtPosition($index, $times = 1)
    {
        $offset = 0;
        $step = 1;

        for ($i = 0; $i < count($this->rules); $i++) {
            if (preg_match('/deal into new stack/', $this->rules[$i])) {
                $step = -$step;

                $offset = (int)gmp_mod(
                    $offset + $step,
                    $this->numCards
                );
            } elseif (preg_match('/cut (-?[0-9]+)/', $this->rules[$i], $matches)) {
                $offset = (int)gmp_mod(
                    $offset + $step * $matches[1],
                    $this->numCards
                );
            } elseif (preg_match('/deal with increment (-?[0-9]+)/', $this->rules[$i], $matches)) {
                $step = (int)gmp_mod(
                    gmp_mul(
                        $step,
                        gmp_invert($matches[1], $this->numCards)
                    ),
                    $this->numCards
                );
            }
        }

        if ($times === 1) {
            return (int)gmp_mod(gmp_mul($step, $index) + $offset, $this->numCards);
        }

        // Following requires numCards to be prime

        // We can represent combined shuffle as (step * x) + offset
        // Iterated function of (step * x) + offset:
        //      (step^n * x) + (offset * ((step^n)-1) * ((step-1)^(-1)))
        // https://en.wikipedia.org/wiki/Iterated_function#Examples

        $res1 = gmp_mul(gmp_powm($step, $times, $this->numCards), $index);

        $res2a = $offset;
        $res2b = gmp_powm($step, $times, $this->numCards) - 1;
        $res2c = gmp_powm($step - 1, $this->numCards - 2, $this->numCards);
        $res2 = gmp_mul($res2a, gmp_mul($res2b, $res2c));

        return (int)gmp_mod($res1 + $res2, $this->numCards);
    }
}
