<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Items;

class ItemsTest extends TestCase
{
    public function testSetAndGetPlayername()
    {
        $item = new Items();
        $playername = 'John Doe';
        $item->setPlayername($playername);

        $this->assertEquals($playername, $item->getPlayername());
    }

    public function testSetAndGetItemname()
    {
        $item = new Items();
        $itemname = 'Sword';
        $item->setItemname($itemname);

        $this->assertEquals($itemname, $item->getItemname());
    }

    public function testSetAndGetAmount()
    {
        $item = new Items();
        $amount = 10;
        $item->setAmount($amount);

        $this->assertEquals($amount, $item->getAmount());
    }
}