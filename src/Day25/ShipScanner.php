<?php

namespace Aoc2019\Day25;

class ShipScanner
{
    protected $computer;

    protected $returnDirection = [
        'north' => 'south',
        'south' => 'north',
        'west' => 'east',
        'east' => 'west',
    ];

    // We don't want to pick up everything we find
    protected $itemsToAvoid = [
        'infinite loop',
        'photons',
        'molten lava',
        'escape pod',
        'giant electromagnet'
    ];

    // Array of path => visited key-values. Example path: 'north north west'
    protected $rooms;

    protected $pathToSecurityCheckpoint;

    // Direction where we should attempt to proceed from security checkpoint
    protected $securityCheckpointPassDirection;

    public function __construct($programString)
    {
        $this->computer = new IntCodeComputer($programString);
    }

    public function findSecurityCode()
    {
        $this->rooms = [
            '' => false,
        ];

        // Run until first input is expected
        $this->runCommand(null);

        while (true) {
            $nextPath = false;

            foreach ($this->rooms as $path => $visited) {
                if ($visited == false) {
                    $nextPath = $path;
                    break;
                }
            }

            if ($nextPath === false) {
                break;
            }

            $this->scanRoom($nextPath);
        }

        $this->moveToRoom($this->pathToSecurityCheckpoint);

        return $this->bypassSecurityCheckpoint();
    }

    protected function scanRoom($path)
    {
        $steps = preg_split('/ /', trim($path), -1, PREG_SPLIT_NO_EMPTY);

        // Dismiss room we came from, this has already been visited
        $dismissDirection = '';
        if (count($steps)) {
            $dismissDirection = $this->returnDirection[$steps[count($steps) - 1]];
        }

        $this->moveToRoom($path);

        $roomDescription = $this->lastCommandResponse;

        $this->addNewUnvisitedRooms($roomDescription, $path, $dismissDirection);

        $this->takeItemsFromRoom($roomDescription);

        // Mark room visited
        $this->rooms[$path] = true;

        $this->returnFromRoom($path);
    }

    protected function runCommand($command = null)
    {
        if ($command) {
            $this->computer->putAsciiInput($command);
        }

        $this->computer->emptyOutput();

        $this->lastCommandResponse = '';

        while (substr($this->lastCommandResponse, -8) != 'Command?') {
            $op = $this->computer->runUntilOutput();

            $this->lastCommandResponse = $this->computer->getAsciiOutput();

            if ($op === 99) {
                return $op;
            }
        }

        return $op;
    }

    protected function moveToRoom($path)
    {
        $steps = preg_split('/ /', trim($path), -1, PREG_SPLIT_NO_EMPTY);

        for ($i = 0; $i < count($steps); $i++) {
            $this->runCommand($steps[$i]);
        }
    }

    protected function returnFromRoom($path)
    {
        $steps = preg_split('/ /', trim($path), -1, PREG_SPLIT_NO_EMPTY);

        for ($i = count($steps) - 1; $i >= 0; $i--) {
            $this->runCommand($this->returnDirection[$steps[$i]]);
        }
    }

    protected function addNewUnvisitedRooms($roomDescription, $currentPath, $dismissDirection)
    {
        $lines = explode("\n", $roomDescription);

        $directions = [];
        $isSecurityCheckpoint = $this->isSecurityCheckpoint($roomDescription);


        foreach ($lines as $line) {
            if (in_array($line, ['- north', '- south', '- west', '- east'])) {
                $direction  = trim(substr($line, 2));

                if ($direction === $dismissDirection) {
                    continue;
                }

                if (!$isSecurityCheckpoint) {
                    $newPath = trim($currentPath . ' ' . $direction);
                    $this->rooms[$newPath] = false;
                } else {
                    $this->pathToSecurityCheckpoint = $currentPath;
                    $this->securityCheckpointPassDirection = $direction;
                }
            }
        }
    }

    protected function takeItemsFromRoom($roomDescription)
    {
        $lines = explode("\n", $roomDescription);

        foreach ($lines as $line) {
            if (substr($line, 0, 2) === '- ' && !in_array($line, ['- north', '- south', '- west', '- east'])) {
                $item = substr($line, 2);

                if (!in_array($item, $this->itemsToAvoid)) {
                    $this->runCommand('take ' . trim($item));
                }
            }
        }
    }

    protected function isSecurityCheckpoint($roomDescription)
    {
        $lines = explode("\n", $roomDescription);

        foreach ($lines as $line) {
            if ($line == '== Security Checkpoint ==') {
                return true;
            }
        }

        return false;
    }

    protected function bypassSecurityCheckpoint()
    {
        $this->runCommand('inv');

        $items = [];
        $outputLines = explode("\n", $this->lastCommandResponse);
        foreach ($outputLines as $line) {
            if (substr($line, 0, 2) == '- ') {
                $items[] = trim(substr($line, 2));
            }
        }

        foreach ($items as $item) {
            $this->runCommand('drop ' . $item);
        }

        // Start combining items and attempting to pass security checkpoint
        // Easiest way is that each item has a bit i.e. we have 2^items bits
        for ($itemCombination = 0; $itemCombination < 2 ** count($items); $itemCombination++) {
            for ($itemIndex = 0; $itemIndex < count($items); $itemIndex++) {
                if ($itemCombination & (1 << $itemIndex)) {
                    $this->runCommand('take ' . $items[$itemIndex]);
                }
            }

            $op = $this->runCommand($this->securityCheckpointPassDirection);

            // Success. We weren't pushed back to security checkpoint so we must
            // have correct mass. Display output containing entry code and bye-bye.
            if ($op === 99) {
                return trim($this->lastCommandResponse);
            }

            for ($itemIndex = 0; $itemIndex < count($items); $itemIndex++) {
                if ($itemCombination & (1 << $itemIndex)) {
                    $this->runCommand('drop ' . $items[$itemIndex]);
                }
            }
        }
    }
}
