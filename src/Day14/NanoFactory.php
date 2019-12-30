<?php

namespace Aoc2019\Day14;

class NanoFactory
{
    protected $reactions;

    public function __construct($reactionString)
    {
        $reactionLines = explode("\n", $reactionString);

        foreach ($reactionLines as $reactionLine) {
            [$source, $product] = explode(' => ', $reactionLine);

            [$productAmount, $productName] = explode(' ', $product);

            $this->reactions[$productName] = [
                'produced_amount' => (int)$productAmount,
                'sources' => [],
            ];

            $sourceItems = explode(', ', $source);

            foreach ($sourceItems as $sourceItem) {
                [$sourceAmount, $sourceName] = explode(' ', $sourceItem);

                $this->reactions[$productName]['sources'][$sourceName] = (int)$sourceAmount;
            }
        }
    }

    protected function getDependencies($product)
    {
        if ($product == 'ORE') {
            return [];
        }

        $dependencies = array_keys($this->reactions[$product]['sources']);

        if ($dependencies == ['ORE']) {
            return $dependencies;
        }

        foreach ($dependencies as $dependency) {
            $subDependencies = $this->getDependencies($dependency);

            foreach ($subDependencies as $subDependecy) {
                if (!in_array($subDependecy, $dependencies)) {
                    $dependencies[] = $subDependecy;
                }
            }
        }

        return $dependencies;
    }

    protected function getNextToDissolve($sources) {
        if (count($sources) == 1) {
            return $sources[0];
        }

        for ($i = 0; $i < count($sources); $i++) {
            $isSuitable = true;

            for ($j = 0; $j < count($sources); $j++) {
                if ($i == $j) {
                    continue;
                }

                if (in_array($sources[$i], $this->getDependencies($sources[$j]))) {
                    $isSuitable = false;
                }
            }

            if ($isSuitable) {
                return $sources[$i];
            }
        }

        return false;
    }

    public function dissolveToOre($productName, $productAmount = 1)
    {
        $stock = [$productName => $productAmount];

        while (array_keys($stock) != ['ORE']) {
            $nextToDissolve = $this->getNextToDissolve(array_keys($stock));
            $targetAmount = $stock[$nextToDissolve];

            if ($nextToDissolve == 'ORE') {
                return;
            }

            foreach ($this->reactions[$nextToDissolve]['sources'] as $sourceName => $sourceAmount) {
                if (!isset($stock[$sourceName])) {
                    $stock[$sourceName] = 0;
                }

                $tmpTargetAmount = $targetAmount;
                $tmpSourceAmount = 0;

                $tmpMultiplier = ceil($tmpTargetAmount / $this->reactions[$nextToDissolve]['produced_amount']);

                $tmpSourceAmount += $tmpMultiplier * $sourceAmount;
                $tmpTargetAmount -= $tmpMultiplier * $this->reactions[$nextToDissolve]['produced_amount'];

                $stock[$sourceName] += $tmpSourceAmount;
            }

            unset($stock[$nextToDissolve]);
        }

        return $stock['ORE'];
    }

    public function produceFromOreAmount($oreAmount, $productName)
    {
        $oreUsedForMillionProduct = $this->dissolveToOre($productName, 1000000);
        $productFromSourceOre = (int)($oreAmount / $oreUsedForMillionProduct * 1000000);

        $oreUsed = $this->dissolveToOre($productName, $productFromSourceOre);
        while ($oreUsed < $oreAmount) {
            $productFromSourceOre++;
            $oreUsed = $this->dissolveToOre($productName, $productFromSourceOre);
        }

        return $productFromSourceOre - 1;
    }
}
