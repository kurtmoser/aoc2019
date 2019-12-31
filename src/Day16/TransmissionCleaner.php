<?php

namespace Aoc2019\Day16;

class TransmissionCleaner
{
    protected $basePattern = [];

    public function setBasePattern($basePattern)
    {
        $this->basePattern = $basePattern;
    }

    protected function buildPatternArray($multiplier)
    {
        $res = [];

        for ($i = 0; $i < count($this->basePattern); $i++) {
            $res = array_merge($res, array_fill(0, $multiplier, $this->basePattern[$i]));
        }

        return $res;
    }

    public function processSignal($signal)
    {
        $signal = '' . $signal;
        $res = '';

        for ($i = 0; $i < strlen($signal); $i++) {
            $tmp = 0;
            $patternArray = $this->buildPatternArray($i + 1);

            for ($j = 0; $j < strlen($signal); $j++) {
                $tmp += (int)$signal[$j] * $patternArray[($j + 1) % count($patternArray)];
            }

            $res .= substr((string)$tmp, -1);
        }

        return $res;
    }

    /**
     * Process second half of input signal
     *
     * Processing second part of the input signal can be optimized/simplified
     * as it relies just on adding operation.
     * Last value in signal (z) will always remain unchanged: z' = z
     * Value just before it (y) will become: y' = (y + z) % 10
     * Following one (x) will become: x' = (x + y + z) % 10 etc
     *
     * P.S. Take note that this is only true for our specific base signal of
     * [0, 1, 0, -1] and it happens because if we were to write out transformation
     * matrix then we would notice that multipliers in the bottom left corner
     * are all 0 and remaining multipliers in the bottom half (i.e. on the right
     * upper side) are all 1.
     *
     * @return void
     */
    public function processSignalEndingPart($signalEndingPart)
    {
        for ($i = strlen($signalEndingPart) - 2; $i >= 0; $i--) {
            $signalEndingPart[$i] = ((int)$signalEndingPart[$i] + (int)$signalEndingPart[$i + 1]) % 10;
        }

        return $signalEndingPart;
    }
}
