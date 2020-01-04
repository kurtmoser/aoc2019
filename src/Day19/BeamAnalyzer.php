<?php

namespace Aoc2019\Day19;

use Aoc2019\Day13\IntCodeComputer;

class BeamAnalyzer
{
    protected $programString;

    public function setProgram($programString)
    {
        $this->programString = $programString;
    }

    public function countAffectedPositions($minX, $minY, $maxX, $maxY)
    {
        $affectedPositions = 0;

        for ($x = $minX; $x <= $maxX; $x++) {
            for ($y = $minY; $y <= $maxY; $y++) {
                $computer = new IntCodeComputer($this->programString);

                $computer->putInput($x);
                $computer->putInput($y);
                $computer->runUntilOutput();

                if ($computer->getOutputArray()[0] == 1) {
                    $affectedPositions++;
                }
            }
        }

        return $affectedPositions;
    }

    public function getMinCoordinatesToFitObject($objectWidth, $objectHeight)
    {
        $x = 0;
        $y = $objectHeight - 1;

        while (true) {
            // Find beam start
            while (true) {
                $computer = new IntCodeComputer($this->programString);

                $computer->putInput($x);
                $computer->putInput($y);
                $computer->runUntilOutput();

                if ($computer->getOutputArray()[0] == 1) {
                    break;
                }

                $x++;
            }

            // Let object's bottom left corner be at (x,y). For object to fit
            // fully into beam, it's top right corner must also be inside beam.

            $computer = new IntCodeComputer($this->programString);

            $computer->putInput($x + ($objectWidth - 1));
            $computer->putInput($y - ($objectHeight - 1));
            $computer->runUntilOutput();

            if ($computer->getOutputArray()[0] == 1) {
                return $x * 10000 + ($y - ($objectHeight - 1));
            }

            $y++;
        }
    }
}
