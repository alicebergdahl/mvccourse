<?php

namespace App\Proj;

use App\Entity\Items;
use App\Repository\ItemsRepository;

class AccessControlService
{
    private ItemsRepository $itemsRepository;

    public function __construct(ItemsRepository $itemsRepository)
    {
        $this->itemsRepository = $itemsRepository;
    }

    public function hasAccessToRoom(int $roomId, int $playerId): array
    {
        switch ($roomId) {
            case 2:
                return $this->checkAccessToRoom2($playerId);
            case 3:
                return $this->checkAccessToRoom3($playerId);
            case 4:
                return $this->checkAccessToRoom4($playerId);
            case 5:
                return $this->checkAccessToRoom5($playerId);
            default:
                return ['access' => true, 'message' => ''];
        }
    }

    private function checkAccessToRoom2(int $playerId): array
    {
        if ($this->playerHasItem($playerId, 'flashlight')) {
            return ['access' => true, 'message' => ''];
        } else {
            return ['access' => false, 'message' => 'Du måste ha en ficklampa för att gå in i grottan!'];
        }
    }

    private function checkAccessToRoom3(int $playerId): array
    {
        if ($this->playerHasItem($playerId, 'fire') && $this->playerHasItem($playerId, 'icepicker')) {
            return ['access' => true, 'message' => ''];
        } else {
            return ['access' => false, 'message' => 'Du måste smälta och hacka bort isen!'];
        }
    }

    private function checkAccessToRoom4(int $playerId): array
    {
        if ($this->playerHasItem($playerId, 'money') && $this->playerHasItem($playerId, 'passport')) {
            return ['access' => true, 'message' => ''];
        } else {
            return ['access' => false, 'message' => 'Du måste betala och visa ditt pass!'];
        }
    }

    private function checkAccessToRoom5(int $playerId): array
    {
        if ($this->playerHasItem($playerId, 'glasses')) {
            return ['access' => true, 'message' => ''];
        } else {
            return ['access' => false, 'message' => 'Du måste ha cyyklop för att vara under vatten!'];
        }
    }

    private function playerHasItem(int $playerId, string $itemName): bool
    {
        $playerItems = $this->itemsRepository->findBy(['playername' => (string) $playerId]);

        foreach ($playerItems as $item) {
            if ($item->getItemname() === $itemName) {
                return true;
            }
        }

        return false;
    }
}