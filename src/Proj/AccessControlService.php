<?php

namespace App\Proj;

use App\Entity\Items;
use App\Repository\ItemsRepository;

/**
 * Serviceklass som hanterar åtkomstkontroll till olika rum.
 */
class AccessControlService
{
    /**
     * @var ItemsRepository|null Repository för föremål.
     */
    private ?ItemsRepository $itemsRepository;

    /**
     * Skapar en AccessControlService.
     *
     * @param ItemsRepository|null $itemsRepository Repository för föremål.
     */
    public function __construct(ItemsRepository $itemsRepository = null)
    {
        $this->itemsRepository = $itemsRepository;
    }

    /**
     * Kontrollerar om en spelare har åtkomst till ett specifikt rum.
     *
     * @param int $roomId ID för rummet.
     * @param $playerId ID för spelaren.
     * @return array<mixed> En array som indikerar om spelaren har åtkomst och ett eventuellt meddelande.
     */
    public function hasAccessToRoom(int $roomId, $playerId): array
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

    /**
     * Kontrollerar åtkomst till rum 2.
     *
     * @param $playerId ID för spelaren.
     * @return array<mixed> En array som indikerar om spelaren har åtkomst och ett eventuellt meddelande.
     */
    private function checkAccessToRoom2($playerId): array
    {
        if ($this->playerHasItem($playerId, 'flashlight')) {
            return ['access' => true, 'message' => ''];
        } else {
            return ['access' => false, 'message' => 'Du måste ha en ficklampa för att gå in i grottan!'];
        }
    }

    /**
    * Kontrollerar åtkomst till rum 3.
    *
    * @param $playerId ID för spelaren.
    * @return array<mixed> En array som indikerar om spelaren har åtkomst och ett eventuellt meddelande.
    */
    private function checkAccessToRoom3($playerId): array
    {
        if ($this->playerHasItem($playerId, 'fire') && $this->playerHasItem($playerId, 'icepicker')) {
            return ['access' => true, 'message' => ''];
        } else {
            return ['access' => false, 'message' => 'Du måste smälta och hacka bort isen!'];
        }
    }

    /**
     * Kontrollerar åtkomst till rum 4.
     *
     * @param $playerId ID för spelaren.
     * @return array<mixed> En array som indikerar om spelaren har åtkomst och ett eventuellt meddelande.
     */
    private function checkAccessToRoom4($playerId): array
    {
        if ($this->playerHasItem($playerId, 'money') && $this->playerHasItem($playerId, 'passport')) {
            return ['access' => true, 'message' => ''];
        } else {
            return ['access' => false, 'message' => 'Du måste betala och visa ditt pass!'];
        }
    }

    /**
     * Kontrollerar åtkomst till rum 5.
     *
     * @param $playerId ID för spelaren.
     * @return array<mixed> En array som indikerar om spelaren har åtkomst och ett eventuellt meddelande.
     */
    private function checkAccessToRoom5($playerId): array
    {
        if ($this->playerHasItem($playerId, 'glasses')) {
            return ['access' => true, 'message' => ''];
        } else {
            return ['access' => false, 'message' => 'Du måste ha cyklop för att vara under vatten!'];
        }
    }

    /**
     * Kontrollerar om en spelare har det angivna föremålet.
     *
     * @param $playerId ID för spelaren.
     * @param string $itemName Namnet på föremålet.
     * @return bool Returnerar true om spelaren har föremålet, annars false.
     */
    private function playerHasItem($playerId, string $itemName): bool
    {
        if ($this->itemsRepository === null) {
            return false;
        }

        $playerItems = $this->itemsRepository->findBy(['playername' => (string) $playerId]);

        foreach ($playerItems as $item) {
            if ($item->getItemname() === $itemName) {
                return true;
            }
        }

        return false;
    }
}