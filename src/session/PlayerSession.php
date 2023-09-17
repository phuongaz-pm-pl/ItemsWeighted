<?php

declare(strict_types=1);

namespace phuongaz\itemsweighted\session;

use phuongaz\itemsweighted\ItemsWeighted;

class PlayerSession {

    private float $overWeight;

    public function __construct(private float $weight = 0.0){
        $this->overWeight = ItemsWeighted::getInstance()->getConfig()->get("over-weight");
    }

    public function getWeight() : float {
        return $this->weight;
    }

    public function setWeight(float $weight) : void {
        $this->weight = $weight;
    }

    public function isOverWeight(float $weight) : bool {
        return $weight >= $this->overWeight;
    }

    public function getOverWeight() : float {
        return $this->overWeight;
    }

    public static function make(float $weight) : self {
        return new PlayerSession($weight);
    }
}