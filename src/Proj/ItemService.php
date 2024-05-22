<?php

namespace App\Proj;

use App\Entity\Items;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Klassen ItemService hanterar operationer relaterade till spelarföremål i databasen.
 */
class ItemService
{
    /**
     * Entitetsmanager för databasinteraktion.
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * Skapar en ny instans av ItemService.
     *
     * @param EntityManagerInterface $entityManager Entitetsmanager för databasinteraktion.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Lägger till ett föremål till spelaren med det angivna spelar-ID:t.
     *
     * @param string $itemName Namnet på föremålet som ska läggas till.
     * @param int $playerId Spelarens ID.
     * @return string
     */
    public function addItemToPlayer(string $itemName, int $playerId): string
    {
        $currentItems = $this->entityManager
            ->getRepository(Items::class)
            ->findBy(['playername' => (string) $playerId]);

        if (count($currentItems) >= 5) {
            return "Backpack full";
        }

        $item = new Items();
        $item->setItemname($itemName);
        $item->setPlayername((string) $playerId);
        $item->setAmount(1);
    
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    
        return "Item added";
    }

    /**
     * Hämtar alla föremål som tillhör en viss spelare.
     *
     * @param int $playerId Spelarens ID.
     * @return array<mixed>
     */
    public function getPlayerItems(int $playerId): array
    {
        return $this->entityManager
            ->getRepository(Items::class)
            ->findBy(['playername' => (string) $playerId]);
    }

    /**
     * Tar bort ett föremål från databasen baserat på föremålets namn.
     *
     * @param string $itemName Namnet på föremålet som ska tas bort.
     * @return void
     */
    public function removeItemByName(string $itemName): void
    {
        $item = $this->entityManager->getRepository(Items::class)->findOneBy(['itemname' => $itemName]);

        if ($item !== null) {
            $this->entityManager->remove($item);
            $this->entityManager->flush();
        }
    }

    /**
     * Kontrollerar om en spelare har ett visst föremål.
     *
     * @param int $playerId Spelarens ID.
     * @param string $itemName Namnet på föremålet som ska kontrolleras.
     * @return bool
     */
    public function playerHasItem(int $playerId, string $itemName): bool
    {
        $playerItems = $this->entityManager
            ->getRepository(Items::class)
            ->findBy(['playername' => (string) $playerId, 'itemname' => $itemName]);

        return count($playerItems) > 0;
    }
}