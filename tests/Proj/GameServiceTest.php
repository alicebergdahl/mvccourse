<?php

namespace App\Tests\Proj;

use PHPUnit\Framework\TestCase;
use App\Proj\GameService;

class GameServiceTest extends TestCase
{
    public function testGetCurrentRoom(): void
    {
        $gameService = new GameService();
        $this->assertEquals(1, $gameService->getCurrentRoom());
    }

    public function testSetCurrentRoom(): void
    {
        $gameService = new GameService();
        $gameService->setCurrentRoom(2);
        $this->assertEquals(2, $gameService->getCurrentRoom());
    }

    public function testGetAvailableDirections(): void
    {
        $gameService = new GameService();
        $expectedDirections = ['east'];
        $this->assertEquals($expectedDirections, $gameService->getAvailableDirections());
    }

    public function testMoveToValidDirection(): void
    {
        $gameService = new GameService();
        $gameService->moveTo('east');
        $this->assertEquals(2, $gameService->getCurrentRoom());
    }

    public function testMoveToInvalidDirection(): void
    {
        $gameService = new GameService();
        $gameService->moveTo('south');
        $this->assertEquals(1, $gameService->getCurrentRoom());
    }
}