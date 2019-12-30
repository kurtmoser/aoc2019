<?php

namespace Aoc2019\Day14;

use PHPUnit\Framework\TestCase;

class Day14Test extends TestCase
{
    /** @test */
    public function nanoFactoryCalculatesNecessaryOreAmountForProduct()
    {
        $reactionString = '10 ORE => 10 A
1 ORE => 1 B
7 A, 1 B => 1 C
7 A, 1 C => 1 D
7 A, 1 D => 1 E
7 A, 1 E => 1 FUEL';

        $nanoFactory = new NanoFactory($reactionString);

        $this->assertEquals(31, $nanoFactory->dissolveToOre('FUEL'));
    }

    /** @test */
    public function nanoFactoryCalculatesProductAmountFromOreAmount()
    {
        $reactionString = '157 ORE => 5 NZVS
165 ORE => 6 DCFZ
44 XJWVT, 5 KHKGT, 1 QDVJ, 29 NZVS, 9 GPVTF, 48 HKGWZ => 1 FUEL
12 HKGWZ, 1 GPVTF, 8 PSHF => 9 QDVJ
179 ORE => 7 PSHF
177 ORE => 5 HKGWZ
7 DCFZ, 7 PSHF => 2 XJWVT
165 ORE => 2 GPVTF
3 DCFZ, 7 NZVS, 5 HKGWZ, 10 PSHF => 8 KHKGT';

        $nanoFactory = new NanoFactory($reactionString);

        $this->assertEquals(82892753, $nanoFactory->produceFromOreAmount(1000000000000, 'FUEL'));
    }
}
