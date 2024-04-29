<?php

namespace App\Card;

/**
 * Klassen CardHand representerar en hand med spelkort.
 */
class CardHand
{
    /**
     * En array av Card-objekt.
     *
     * @var Card[]
     */
    protected $cards = [];

    /**
     * Lägger till ett kort i handen.
     *
     * @param Card $card Kortet som ska läggas till.
     */
    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * Hämtar alla kort i handen.
     *
     * @return array<Card>
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Returnerar en strängrepresentation av korten i handen.
     *
     * @return string
     */
    public function __toString(): string
    {
        $handString = '';
        foreach ($this->cards as $card) {
            $handString .= $card . "\n";
        }
        return $handString;
    }
}
