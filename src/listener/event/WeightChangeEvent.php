<?php

declare(strict_types=1);

namespace phuongaz\itemsweighted\listener\event;

use ColinHDev\libAsyncEvent\AsyncEvent;
use ColinHDev\libAsyncEvent\ConsecutiveEventHandlerExecutionTrait;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class WeightChangeEvent extends PlayerEvent implements AsyncEvent {
    use ConsecutiveEventHandlerExecutionTrait, CancellableTrait;

    public function __construct(
        Player $player,
        private float $from,
        private float $to) {
        $this->player = $player;
    }

    public function getFrom() : float {
        return $this->from;
    }

    public function getTo() : float {
        return $this->to;
    }
}