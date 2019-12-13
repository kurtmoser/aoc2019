<?php

namespace Aoc2019\Day12;

class GravitySimulator
{
    public $objects;

    public function addObject($object)
    {
        $this->objects[] = $object;
    }

    public function recalculateVelocities()
    {
        $this->recalculateAxisVelocities(0);
        $this->recalculateAxisVelocities(1);
        $this->recalculateAxisVelocities(2);
    }

    public function recalculateAxisVelocities($axis)
    {
        for ($i = 0; $i < count($this->objects) - 1; $i++) {
            for ($j = $i + 1; $j < count($this->objects); $j++) {
                if ($this->getObject($i)->getPosition($axis) < $this->getObject($j)->getPosition($axis)) {
                    $this->getObject($i)->increaseVelocityCache($axis);
                    $this->getObject($j)->decreaseVelocityCache($axis);
                } elseif ($this->getObject($i)->getPosition($axis) > $this->getObject($j)->getPosition($axis)) {
                    $this->getObject($i)->decreaseVelocityCache($axis);
                    $this->getObject($j)->increaseVelocityCache($axis);
                }
            }
        }

        for ($i = 0; $i < count($this->objects); $i++) {
            $newVelocity = $this->getObject($i)->getVelocity($axis) + $this->getObject($i)->getVelocityCache($axis);
            $this->getObject($i)->setVelocity($axis, $newVelocity);

            // Reset velocityCache for the next iteration
            $this->getObject($i)->resetVelocityCache($axis);
        }
    }

    public function recalculatePositions()
    {
        $this->recalculateAxisPositions(0);
        $this->recalculateAxisPositions(1);
        $this->recalculateAxisPositions(2);
    }

    public function recalculateAxisPositions($axis)
    {
        for ($i = 0; $i < count($this->objects); $i++) {
            $newPosition = $this->getObject($i)->getPosition($axis) + $this->getObject($i)->getVelocity($axis);
            $this->getObject($i)->setPosition($axis, $newPosition);
        }
    }

    public function getObjects()
    {
        return $this->objects;
    }

    public function getObject($index)
    {
        return $this->objects[$index];
    }

    public function calculateTotalEnergy()
    {
        $totalEnergy = 0;

        foreach ($this->objects as $object) {
            $totalEnergy += $object->getTotalEnergy();
        }

        return $totalEnergy;
    }
}
