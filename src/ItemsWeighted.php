<?php

declare(strict_types=1);

namespace phuongaz\itemsweighted;

use phuongaz\itemsweighted\listener\EventHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class ItemsWeighted extends PluginBase {
    use SingletonTrait;

    public function onLoad(): void {
        self::setInstance($this);
    }

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler(), $this);
    }

}