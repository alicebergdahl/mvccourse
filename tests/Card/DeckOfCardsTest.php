<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\DeckOfCards;
use App\Card\CardHand;

class DeckOfCardsTest extends TestCase
{
    public function testDeckInitialization(): void
    {
        $deck = new DeckOfCards();
        $this->assertCount(52, $deck->getCards());
    }

    public function testShuffle(): void
    {
        $deck = new DeckOfCards();
        $beforeShuffle = $deck->getCards();
        $deck->shuffle();
        $afterShuffle = $deck->getCards();
        $this->assertCount(52, $afterShuffle);
        $this->assertNotEquals($beforeShuffle, $afterShuffle);
    }

    public function testDeal(): void
    {
        $deck = new DeckOfCards();
        $hand = $deck->deal(5);
        $this->assertInstanceOf(CardHand::class, $hand);
        $this->assertCount(5, $hand->getCards());
        $this->assertCount(47, $deck->getCards());
    }

    public function testSortCards(): void
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $deck->sortCards();
        $cards = $deck->getCards();
        $this->assertEquals('♥', $cards[0]->getSuit());
        $this->assertEquals('A', $cards[0]->getValue());
    }
}
