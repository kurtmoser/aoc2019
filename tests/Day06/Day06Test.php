<?php

namespace Aoc2019\Day06;

use PHPUnit\Framework\TestCase;

class Day06Test extends TestCase
{
    /** @test */
    public function orbitMapCountsCumulativeOrbits()
    {
        $orbits = explode("\n", 'COM)B
B)C
C)D
D)E
E)F
B)G
G)H
D)I
E)J
J)K
K)L');

        $orbitMap = new OrbitMap;

        foreach ($orbits as $orbit) {
            [$parent, $child] = explode(')', $orbit);

            $orbitMap->addOrbit($parent, $child);
        }

        $this->assertEquals(42, $orbitMap->getCumulativeOrbits());
    }

    /** @test */
    public function orbitMapCountsOrbitalTransfersBetweenObjects()
    {
        $orbits = explode("\n", 'COM)B
B)C
C)D
D)E
E)F
B)G
G)H
D)I
E)J
J)K
K)L
K)YOU
I)SAN');

        $orbitMap = new OrbitMap;

        foreach ($orbits as $orbit) {
            [$parent, $child] = explode(')', $orbit);

            $orbitMap->addOrbit($parent, $child);
        }

        $this->assertEquals(4, $orbitMap->getOrbitalTransfers('YOU', 'SAN'));
    }
}
