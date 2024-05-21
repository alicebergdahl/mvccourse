<?php

namespace Tests\App\Proj;

use PHPUnit\Framework\TestCase;
use App\Proj\AccessControlService;

class AccessControlServiceTest extends TestCase
{
    private $accessControlService;
    private $itemsRepositoryStub;

    protected function setUp(): void
    {
        // Set up the AccessControlService with a stub ItemsRepository
        $this->itemsRepositoryStub = $this->createStub(\App\Repository\ItemsRepository::class);
        $this->accessControlService = new AccessControlService($this->itemsRepositoryStub);
    }

    public function testCheckNoAccessToRoom2(): void
    {
        // Configure the stub to return an empty array
        $this->itemsRepositoryStub->method('findBy')
            ->willReturn([]);

        // Player should not have access to room 2 without required item
        $playerId = 1;
        $accessResult = $this->accessControlService->hasAccessToRoom(2, $playerId);
        $this->assertFalse($accessResult['access']);
    }

    public function testCheckNoAccessToRoom3(): void
    {
        // Configure the stub to return an empty array
        $this->itemsRepositoryStub->method('findBy')
            ->willReturn([]);

        // Player should not have access to room 3 without required items
        $playerId = 1;
        $accessResult = $this->accessControlService->hasAccessToRoom(3, $playerId);
        $this->assertFalse($accessResult['access']);
    }

    public function testCheckNoAccessToRoom4(): void
    {
        // Configure the stub to return an empty array
        $this->itemsRepositoryStub->method('findBy')
            ->willReturn([]);

        // Player should not have access to room 4 without required items
        $playerId = 1;
        $accessResult = $this->accessControlService->hasAccessToRoom(4, $playerId);
        $this->assertFalse($accessResult['access']);
    }

    public function testCheckNoAccessToRoom5(): void
    {
        // Configure the stub to return an empty array
        $this->itemsRepositoryStub->method('findBy')
            ->willReturn([]);

        // Player should not have access to room 5 without required item
        $playerId = 1;
        $accessResult = $this->accessControlService->hasAccessToRoom(5, $playerId);
        $this->assertFalse($accessResult['access']);
    }
}