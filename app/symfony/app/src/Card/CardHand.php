<?php

namespace App\Card;

class CardHand
{
    protected $cards = [];

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function __toString(): string
    {
        $handString = '';
        foreach ($this->cards as $card) {
            $handString .= $card . "\n";
        }
        return $handString;
    }
}
