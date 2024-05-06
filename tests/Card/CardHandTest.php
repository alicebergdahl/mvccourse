<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;
use App\Card\CardHand;

class CardHandTest extends TestCase
{
    public function testAddAndGetCards(): void
    {
        $hand = new CardHand();
        $card1 = new Card('♠', 'A');
        $card2 = new Card('♦', '10');

        $hand->addCard($card1);
        $hand->addCard($card2);

        $this->assertCount(2, $hand->getCards());
        $this->assertContainsOnlyInstancesOf(Card::class, $hand->getCards());
    }

    public function testToString(): void
    {
        $hand = new CardHand();
        $hand->addCard(new Card('♠', 'A'));
        $hand->addCard(new Card('♦', '10'));

        $expectedString = "A ♠\n10 ♦\n";
        $this->assertEquals($expectedString, $hand->__toString());
    }
}
