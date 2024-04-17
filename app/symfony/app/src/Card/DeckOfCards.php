<?php

namespace App\Card;

class DeckOfCards
{
    protected $cards = [];

    public function __construct()
    {
        $suits = ['♥', '♦', '♣', '♠'];
        $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new Card($suit, $value);
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function deal(int $number): CardHand
    {
        $hand = new CardHand();
        for ($i = 0; $i < $number; $i++) {
            $randomIndex = array_rand($this->cards, 1);
            $card = $this->cards[$randomIndex];
            unset($this->cards[$randomIndex]);
            $hand->addCard($card);
        }
        return $hand;
    }

    public function sortCards(): void
    {
        $suitsOrder = ['♥', '♦', '♣', '♠'];
        $valuesOrder = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        usort($this->cards, function ($a, $b) use ($suitsOrder, $valuesOrder) {
            $aSuitIndex = array_search($a->getSuit(), $suitsOrder);
            $bSuitIndex = array_search($b->getSuit(), $suitsOrder);

            if ($aSuitIndex !== $bSuitIndex) {
                return $aSuitIndex - $bSuitIndex;
            } else {
                $aValueIndex = array_search($a->getValue(), $valuesOrder);
                $bValueIndex = array_search($b->getValue(), $valuesOrder);
                return $aValueIndex - $bValueIndex;
            }
        });
    }

    public function getCards(): array
    {
        return $this->cards;
    }
}
