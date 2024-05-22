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
        $this->itemsRepositoryStub = $this->createStub(\App\Repository\ItemsRepository::class);
        $this->accessControlService = new AccessControlService($this->itemsRepositoryStub);
    }

    public function testCheckNoAccessToRoom2(): void
    {
        $this->itemsRepositoryStub->method('findBy')
            ->willReturn([]);

        $playerId = 1;
        $accessResult = $this->accessControlService->hasAccessToRoom(2, $playerId);
        $this->assertFalse($accessResult['access']);
    }

    public function testCheckNoAccessToRoom3(): void
    {
        $this->itemsRepositoryStub->method('findBy')
            ->willReturn([]);

        $playerId = 1;
        $accessResult = $this->accessControlService->hasAccessToRoom(3, $playerId);
        $this->assertFalse($accessResult['access']);
    }

    public function testCheckNoAccessToRoom4(): void
    {
        $this->itemsRepositoryStub->method('findBy')
            ->willReturn([]);

        $playerId = 1;
        $accessResult = $this->accessControlService->hasAccessToRoom(4, $playerId);
        $this->assertFalse($accessResult['access']);
    }

    public function testCheckNoAccessToRoom5(): void
    {
        $this->itemsRepositoryStub->method('findBy')
            ->willReturn([]);

        $playerId = 1;
        $accessResult = $this->accessControlService->hasAccessToRoom(5, $playerId);
        $this->assertFalse($accessResult['access']);
    }
}