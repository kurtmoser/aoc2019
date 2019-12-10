<?php

namespace Aoc2019\Day01;

class Day01Solver
{
    public function calculateFuel(array $moduleMasses)
    {
        $requiredFuel = 0;

        foreach ($moduleMasses as $moduleMass) {
            $requiredFuel += $this->calculateModuleFuel($moduleMass);
        }

        return $requiredFuel;
    }

    public function calculateRecursiveFuel(array $moduleMasses)
    {
        $requiredFuel = 0;

        foreach ($moduleMasses as $moduleMass) {
            $tmpFuel = $this->calculateModuleFuel($moduleMass);

            while ($tmpFuel > 0) {
                $requiredFuel += $tmpFuel;
                $tmpFuel = $this->calculateModuleFuel($tmpFuel);
            }
        }

        return $requiredFuel;
    }

    public function calculateModuleFuel($moduleMass)
    {
        $requiredFuel = (int)($moduleMass / 3) - 2;

        if ($requiredFuel < 0) {
            $requiredFuel = 0;
        }

        return $requiredFuel;
    }
}
