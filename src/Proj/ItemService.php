<?php

namespace App\Proj;

use App\Entity\Items;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addItemToPlayer(string $itemName, int $playerId): string
    {
        $currentItems = $this->entityManager
            ->getRepository(Items::class)
            ->findBy(['playername' => (string) $playerId]);

        if (count($currentItems) >= 5) {
            return "Din ryggsäck är full!";
        }

        $item = new Items();
        $item->setItemname($itemName);
        $item->setPlayername((string) $playerId);
        $item->setAmount(1);
    
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    
        return "Föremål tillagt.";
    }

    public function getPlayerItems(int $playerId): array
    {
        return $this->entityManager
            ->getRepository(Items::class)
            ->findBy(['playername' => (string) $playerId]);
    }

    public function removeItemByName(string $itemName): void
    {
        $item = $this->entityManager->getRepository(Items::class)->findOneBy(['itemname' => $itemName]);

        if ($item !== null) {
            $this->entityManager->remove($item);
            $this->entityManager->flush();
        }
    }

    public function playerHasItem(int $playerId, string $itemName): bool
    {
        $playerItems = $this->entityManager
            ->getRepository(Items::class)
            ->findBy(['playername' => (string) $playerId, 'itemname' => $itemName]);

        return count($playerItems) > 0;
    }
}