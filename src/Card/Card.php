<?php

namespace App\Card;

/**
 * Klassen Card representerar ett enskild spelkort med en färg och ett värde.
 */
class Card
{
    /**
     * Kortets färg.
     *
     * @var string
     */
    protected $suit;

    /**
     * Kortets värde.
     *
     * @var string
     */
    protected $value;

    /**
     * Skapar ett nytt kort.
     *
     * @param string $suit Kortets färg.
     * @param string $value Kortets värde.
     */
    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    /**
     * Hämtar kortets färg.
     *
     * @return string
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Hämtar kortets värde.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returnerar en strängrepresentation av kortet.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->value} {$this->suit}";
    }
}
