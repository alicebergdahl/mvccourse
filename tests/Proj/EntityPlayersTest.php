<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Players;

class PlayersTest extends TestCase
{
    public function testSetAndGetPlayername()
    {
        $player = new Players();
        $playername = 'John Doe';
        $player->setPlayername($playername);

        $this->assertEquals($playername, $player->getPlayername());
    }
}