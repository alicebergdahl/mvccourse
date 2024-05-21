<?php

namespace App\Tests\Proj;

use App\Entity\Items;
use App\Proj\ItemService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class ItemServiceTest extends TestCase
{
    private $entityManagerMock;
    private $itemService;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->itemService = new ItemService($this->entityManagerMock);
    }

    public function testAddItemToPlayer_RyggsackFull()
    {
        // Mock current items with a count >= 5
        $repositoryMock = $this->createMock(EntityRepository::class); // Create a mock of EntityRepository
        $repositoryMock->method('findBy')->willReturn([1, 2, 3, 4, 5]); // Mock the findBy method
        $this->entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        $result = $this->itemService->addItemToPlayer("NewItem", 123);

        $this->assertEquals("Din ryggsäck är full!", $result);
    }

    // Test for line 40-42
    public function testGetPlayerItems()
    {
        // Mocking EntityManager's getRepository and findBy methods
        $repositoryMock = $this->createMock(EntityRepository::class); // Create a mock of EntityRepository
        $repositoryMock->method('findBy')->willReturn([123]); // Mock the findBy method
        $this->entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        $result = $this->itemService->getPlayerItems(123);

        $this->assertNotEmpty($result);
    }

    // Test for lines 57-61
    public function testPlayerHasItem()
    {
        // Mocking EntityManager's getRepository and findBy methods
        $repositoryMock = $this->createMock(EntityRepository::class); // Create a mock of EntityRepository
        $repositoryMock->method('findBy')->willReturn(["someItem"]); // Mock the findBy method
        $this->entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        $result = $this->itemService->playerHasItem(123, "TestItem");

        $this->assertTrue($result);
    }
}