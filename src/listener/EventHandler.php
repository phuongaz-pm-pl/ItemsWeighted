<?php

declare(strict_types=1);

namespace phuongaz\itemsweighted\listener;

use phuongaz\itemsweighted\IWAPI;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\player\Player;

class EventHandler implements Listener {

    public function onTransaction(InventoryTransactionEvent $event): void {
        IWAPI::updateWeightAndSpeed($event->getTransaction()->getSource());
    }

    public function onPickUp(EntityItemPickupEvent $event): void {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            IWAPI::updateWeightAndSpeed($entity);
        }
    }

    public function onJoin(PlayerJoinEvent $event): void {
        IWAPI::updateWeightAndSpeed($event->getPlayer());
    }

    public function onMove(PlayerMoveEvent $event): void {
        $player = $event->getPlayer();
        $session = IWAPI::getWeight($player);
        $totalWeightChange = $session->getWeight();

        if ($session->isOverWeight($totalWeightChange)) {
            $player->sendPopup("Inventory is too heavy to move.");
            $event->cancel();
        }
    }
}
