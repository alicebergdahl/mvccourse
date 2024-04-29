<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;

class CardTest extends TestCase
{
    public function testCardCreation(): void
    {
        $card = new Card('♠', 'A');
        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals('♠', $card->getSuit());
        $this->assertEquals('A', $card->getValue());
    }

    public function testToString(): void
    {
        $card = new Card('♠', 'A');
        $this->assertEquals("A ♠", $card->__toString());
    }
}