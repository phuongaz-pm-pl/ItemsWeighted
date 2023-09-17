<?php

declare(strict_types=1);

namespace phuongaz\itemsweighted;

use phuongaz\itemsweighted\listener\event\WeightChangeEvent;
use phuongaz\itemsweighted\session\PlayerSession;
use phuongaz\itemsweighted\session\WeightPool;
use pocketmine\entity\Attribute;
use pocketmine\entity\AttributeFactory;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\player\Player;

class IWAPI {

    public static function getPlugin() : ItemsWeighted {
        return ItemsWeighted::getInstance();
    }

    public static function getWeight(Player $player) : PlayerSession {
        return WeightPool::get($player);
    }

    public static function getWeightOfItem(Item $item): float {
        $weightConfig = self::getPlugin()->getConfig()->get("items");
        $itemAlias = StringToItemParser::getInstance()->lookupAliases($item);

        $totalWeight = 0.0;

        foreach ($itemAlias as $itemName) {
            if (isset($weightConfig[$itemName])) {
                $totalWeight += (float)$weightConfig[$itemName];
            }
        }

        return $totalWeight;
    }

    public static function updateWeightAndSpeed(Player $player): void {
        $session = IWAPI::getWeight($player);
        $sourceWeight = $session->getWeight();
        $targetWeight = 0.0;

        foreach ($player->getInventory()->getContents() as $item) {
            $targetWeight += IWAPI::getWeightOfItem($item) * $item->getCount();
        }

        $event = new WeightChangeEvent($player, $sourceWeight, $targetWeight);
        $event->setCallback(function (WeightChangeEvent $event) use ($player, $session) {
            if ($event->isCancelled()) {
                return;
            }

            $defaultSpeed = AttributeFactory::getInstance()->mustGet(Attribute::MOVEMENT_SPEED)->getValue();
            $after = ($event->getTo() / $session->getOverWeight()) / 100;
            $newSpeed = $defaultSpeed - $after;
            $newSpeed = max(0, min(1, $newSpeed));
            $player->setMovementSpeed($newSpeed);
            WeightPool::update($player, $session);
        });
        $event->call();
    }
}
