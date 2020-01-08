<?php

namespace Aoc2019\Day23;

use Aoc2019\Day13\IntCodeComputer;

class NetworkInterfaceController
{
    protected $network;

    public function loadProgram($programString)
    {
        $this->network = [];

        for ($i = 0; $i < 50; $i++) {
            $this->network[] = new IntCodeComputer($programString);
            $this->input[$i][] = $i;
            $this->output[$i] = [];
            $this->isIdle[$i] = false;
        }
    }

    protected function runSingleTickOnNetwork()
    {
        for ($i = 0; $i < 50; $i++) {
            $nextOp = $this->network[$i]->getNextOp();

            if ($nextOp == 3) {
                if (count($this->input[$i])) {
                    $this->network[$i]->putInput(array_shift($this->input[$i]));
                    $this->isIdle[$i] = false;
                } else {
                    $this->network[$i]->putInput(-1);
                    $this->isIdle[$i] = true;
                }
            }

            $this->network[$i]->runOp();

            if ($nextOp == 4) {
                $this->output[$i][] = $this->network[$i]->getOutputArray()[count($this->network[$i]->getOutputArray()) - 1];
            }
        }
    }

    protected function isNetworkIdle()
    {
        for ($i = 0; $i < 50; $i++) {
            if (!$this->isIdle[$i]) {
                return false;
            }
        }

        return true;
    }

    protected function moveInfoBetweenComputers()
    {
        for ($i = 0; $i < 50; $i++) {
            if (count($this->output[$i]) == 3) {
                $this->input[$this->output[$i][0]][] = $this->output[$i][1];
                $this->input[$this->output[$i][0]][] = $this->output[$i][2];

                $this->output[$i] = [];
            }
        }
    }

    public function getFirstNatPackage()
    {
        while (true) {
            $this->runSingleTickOnNetwork();

            $this->moveInfoBetweenComputers();

            if (!empty($this->input[255])) {
                return [
                    'x' => $this->input[255][0],
                    'y' => $this->input[255][1],
                ];
            }
        }
    }

    public function getFirstResentNatPackage()
    {
        $natValueSent = false;
        $prevNatY = null;

        while (true) {
            $this->runSingleTickOnNetwork();

            $this->moveInfoBetweenComputers();

            if (!empty($this->input[255])) {
                $natValueSent = true;

                $natX = $this->input[255][0];
                $natY = $this->input[255][1];

                $this->input[255] = null;
            }

            if ($this->isNetworkIdle() && $natValueSent) {
                $natValueSent = false;

                $this->network[0]->putInput($natX);
                $this->network[0]->putInput($natY);
                $this->isIdle[0] = false;

                if ($natY == $prevNatY) {
                    return [
                        'x' => $natX,
                        'y' => $natY,
                    ];
                }

                $prevNatY = $natY;
            }
        }
    }
}
