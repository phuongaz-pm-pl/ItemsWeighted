<?php

declare(strict_types=1);

namespace phuongaz\itemsweighted\session;

use phuongaz\itemsweighted\IWAPI;
use pocketmine\player\Player;
use WeakMap;

class WeightPool {

    private static WeakMap $weights;

    public static function get(Player $player) : PlayerSession {
        if(!isset(self::$weights)) {
            $map = new WeakMap();
            self::$weights = $map;
        }
        return self::$weights[$player] ?? self::load($player);
    }

    public static function update(Player $player, PlayerSession $newSession): void {
        if (isset(self::$weights[$player])) {
            unset(self::$weights[$player]);
        }
        self::$weights[$player] = $newSession;
    }

    public static function load(Player $player) : PlayerSession {
        $weight = 0.0;
        foreach($player->getInventory()->getContents() as $item) {
            $weight += IWAPI::getWeightOfItem($item) * $item->getCount();
        }
        return PlayerSession::make($weight);
    }
}
