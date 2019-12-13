<?php

namespace Aoc2019\Day12;

class GravityObject
{
    private $position = [0, 0, 0];

    private $velocity = [0, 0, 0];

    private $velocityCache = [0, 0, 0];

    public function __construct($position = null, $velocity = null)
    {
        if ($position) {
            $this->position = $position;
        }

        if ($velocity) {
            $this->velocity = $velocity;
        }
    }

    public function getPositionArray()
    {
        return $this->position;
    }

    public function getPosition($axis)
    {
        return $this->position[$axis];
    }

    public function setPosition($axis, $value)
    {
        $this->position[$axis] = $value;
    }

    public function getVelocityArray()
    {
        return $this->velocity;
    }

    public function getVelocity($axis)
    {
        return $this->velocity[$axis];
    }

    public function setVelocity($axis, $value)
    {
        $this->velocity[$axis] = $value;
    }

    public function getVelocityCache($axis)
    {
        return $this->velocityCache[$axis];
    }

    public function increaseVelocityCache($axis)
    {
        $this->velocityCache[$axis]++;
    }

    public function decreaseVelocityCache($axis)
    {
        $this->velocityCache[$axis]--;
    }

    public function resetVelocityCache($axis)
    {
        $this->velocityCache[$axis] = 0;
    }

    public function getTotalEnergy()
    {
        return $this->getPotentialEnergy() * $this->getKineticEnergy();
    }

    public function getPotentialEnergy()
    {
        return abs($this->position[0]) + abs($this->position[1]) + abs($this->position[2]);
    }

    public function getKineticEnergy()
    {
        return abs($this->velocity[0]) + abs($this->velocity[1]) + abs($this->velocity[2]);
    }
}
