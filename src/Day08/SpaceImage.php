<?php

namespace Aoc2019\Day08;

class SpaceImage
{
    protected $width;

    protected $height;

    protected $data;

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function setData($string)
    {
        $this->data = $string;
    }

    public function countLayers()
    {
        return strlen($this->data) / ($this->width * $this->height);
    }

    public function countDigitsInLayer($layer, $digit)
    {
        $count = 0;

        for ($i = $layer * $this->width * $this->height; $i < ($layer + 1) * $this->width * $this->height; $i++) {
            if ((int)$this->data[$i] === $digit) {
                $count++;
            }
        }

        return $count;
    }

    public function printFlattened()
    {
        $flattened = str_repeat('2', $this->width * $this->height);

        for ($pixel = 0; $pixel < $this->width * $this->height; $pixel++) {
            for ($layer = 0; $layer < $this->countLayers(); $layer++) {
                if ($this->data[$layer * $this->width * $this->height + $pixel] != 2) {
                    $flattened[$pixel] = $this->data[$layer * $this->width * $this->height + $pixel];

                    continue 2;
                }
            }
        }

        $runner = 0;

        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                switch ($flattened[$runner++]) {
                    case '0':
                    case '2':
                        echo ' ';
                        break;
                    case '1':
                        echo 'X';
                        break;
                }
            }

            echo PHP_EOL;
        }
    }
}
