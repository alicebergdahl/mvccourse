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

    public function testAddItemToPlayer_AddItemSuccess()
    {
        // Mock current items with a count < 5
        $repositoryMock = $this->createMock(EntityRepository::class); // Create a mock of EntityRepository
        $repositoryMock->method('findBy')->willReturn([1, 2, 3, 4]); // Mock the findBy method
        $this->entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        $result = $this->itemService->addItemToPlayer("NewItem", 123);

        $this->assertEquals("Föremål tillagt.", $result);
    }

    // Test för raderna 33-37
    public function testRemoveItemByName_ItemExists()
    {
        // Mocking EntityManager's getRepository and findOneBy methods
        $repositoryMock = $this->createMock(EntityRepository::class); // Create a mock of EntityRepository
        $repositoryMock->method('findOneBy')->willReturn(new Items()); // Mock the findOneBy method
        $this->entityManagerMock->method('getRepository')->willReturn($repositoryMock);
    
        // Expect that removeItemByName method is called once with the specified argument
        $this->entityManagerMock->expects($this->once())->method('remove')->with($this->isInstanceOf(Items::class));
    
        // Call the method under test
        $this->itemService->removeItemByName("ExistingItem");
    }

    // Test för raderna 42-45
    public function testPlayerHasItem_PlayerHasItem()
    {
        // Mocking EntityManager's getRepository and findBy methods
        $repositoryMock = $this->createMock(EntityRepository::class); // Create a mock of EntityRepository
        $repositoryMock->method('findBy')->willReturn(["TestItem"]); // Mock the findBy method
        $this->entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        $result = $this->itemService->playerHasItem(123, "TestItem");

        $this->assertTrue($result);
    }
}