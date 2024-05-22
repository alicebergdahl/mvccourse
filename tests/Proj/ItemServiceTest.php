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
        $repositoryMock = $this->createMock(EntityRepository::class);
        $repositoryMock->method('findBy')->willReturn([1, 2, 3, 4, 5]);
        $this->entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        $result = $this->itemService->addItemToPlayer("NewItem", 123);

        $this->assertEquals("Backpack full", $result);
    }

    public function testGetPlayerItems()
    {
        $repositoryMock = $this->createMock(EntityRepository::class);
        $repositoryMock->method('findBy')->willReturn([123]);
        $this->entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        $result = $this->itemService->getPlayerItems(123);

        $this->assertNotEmpty($result);
    }

    public function testPlayerHasItem()
    {
        $repositoryMock = $this->createMock(EntityRepository::class);
        $repositoryMock->method('findBy')->willReturn(["someItem"]);
        $this->entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        $result = $this->itemService->playerHasItem(123, "TestItem");

        $this->assertTrue($result);
    }

    public function testAddItemToPlayer_AddItemSuccess()
    {
        $repositoryMock = $this->createMock(EntityRepository::class);
        $repositoryMock->method('findBy')->willReturn([1, 2, 3, 4]);
        $this->entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        $result = $this->itemService->addItemToPlayer("NewItem", 123);

        $this->assertEquals("Item added", $result);
    }

    public function testRemoveItemByName_ItemExists()
    {
        $repositoryMock = $this->createMock(EntityRepository::class);
        $repositoryMock->method('findOneBy')->willReturn(new Items());
        $this->entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        $this->entityManagerMock->expects($this->once())->method('remove')->with($this->isInstanceOf(Items::class));

        $this->itemService->removeItemByName("ExistingItem");
    }

    public function testPlayerHasItem_PlayerHasItem()
    {
        $repositoryMock = $this->createMock(EntityRepository::class);
        $repositoryMock->method('findBy')->willReturn(["TestItem"]);
        $this->entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        $result = $this->itemService->playerHasItem(123, "TestItem");

        $this->assertTrue($result);
    }
}