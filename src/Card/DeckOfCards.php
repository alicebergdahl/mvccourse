<?php

namespace App\Card;

/**
 * Klassen DeckOfCards representerar en hel kortlek.
 */
class DeckOfCards
{
    /**
     * En array av Card-objekt som utgör kortleken.
     *
     * @var Card[]
     */
    protected array $cards = [];

    /**
     * Konstruktor som skapar en komplett kortlek.
     */
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

    /**
     * Blandar kortleken.
     */
    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    /**
     * Delar ut ett antal kort och returnerar dem som en CardHand.
     *
     * @param int $number Antal kort som ska delas ut.
     * @return CardHand En hand med de utdelade korten.
     */
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

    /**
     * Sorterar kortleken baserat på färg och värde.
     */
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

    /**
     * Hämtar alla kort i kortleken.
     *
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }
}
