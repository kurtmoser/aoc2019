<?php

namespace Aoc2019\Day18;

use PHPUnit\Framework\TestCase;

class Day18Test extends TestCase
{
    /** @test */
    public function keyCollectorCalculatesStepsForSimpleMap()
    {
        $this->assertTrue(true);

        $keyCollector = new KeyCollector;

        $keyCollector->loadMap('#########
#b.A.@.a#
#########');

        $this->assertEquals(8, $keyCollector->calculateMinSteps('@'));
    }

    /** @test */
    public function keyCollectorCalculatesStepsForMoreComplexMap()
    {
        $this->assertTrue(true);

        $keyCollector = new KeyCollector;

        $keyCollector->loadMap('#################
#i.G..c...e..H.p#
########.########
#j.A..b...f..D.o#
########@########
#k.E..a...g..B.n#
########.########
#l.F..d...h..C.m#
#################');

        $this->assertEquals(136, $keyCollector->calculateMinSteps('@'));
    }
}
