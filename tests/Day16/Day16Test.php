<?php

namespace Aoc2019\Day16;

use PHPUnit\Framework\TestCase;

class Day16Test extends TestCase
{
    /** @test */
    public function transmissionCleanerProcessesInputSignal()
    {
        $transmissionCleaner = new TransmissionCleaner;

        $transmissionCleaner->setBasePattern([0, 1, 0, -1]);

        $signal = '12345678';

        for ($i = 0; $i < 4; $i++) {
            $signal = $transmissionCleaner->processSignal($signal);
        }

        $this->assertEquals('01029498', $signal);
    }

    /** @test */
    public function transmissionCleanerProcessesInputSignalEndingPart()
    {
        $transmissionCleaner = new TransmissionCleaner;

        $transmissionCleaner->setBasePattern([0, 1, 0, -1]);

        $signal = '03036732577212944063491565474664';
        $offset = (int)substr($signal, 0, 7);
        $signal = str_repeat($signal, 10000);
        $signal = substr($signal, $offset);

        for ($i = 0; $i < 100; $i++) {
            $signal = $transmissionCleaner->processSignalEndingPart($signal);
        }

        $this->assertEquals('84462026', substr($signal, 0, 8));
    }
}
