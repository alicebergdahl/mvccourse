<?php

namespace App\Proj;

/**
 * Klassen GameService hanterar spelets logik, inklusive navigering mellan rum.
 */
class GameService
{
    /**
     * Aktuellt rum där spelaren befinner sig.
     *
     * @var int
     */
    private $currentRoom = 1;

    /**
     * Definition av olika rum och deras anslutningar till andra rum.
     *
     * @var array<mixed>
     */
    private $rooms = [
        1 => ['east' => 2],
        2 => ['east' => 3, 'west' => 1, 'south' => 4],
        3 => ['west' => 2],
        4 => ['north' => 2, 'east' => 5],
        5 => [],
    ];

    /**
     * Returnerar det aktuella rummet där spelaren befinner sig.
     *
     * @return int
     */
    public function getCurrentRoom(): int
    {
        return $this->currentRoom;
    }

    /**
     * Sätter det aktuella rummet där spelaren befinner sig.
     *
     * @param int $room
     * @return void
     */
    public function setCurrentRoom(int $room): void
    {
        $this->currentRoom = $room;
    }

    /**
     * Returnerar en lista över tillgängliga riktningar från det aktuella rummet.
     *
     * @return array<mixed>
     */
    public function getAvailableDirections(): array
    {
        return array_keys($this->rooms[$this->currentRoom]);
    }

    /**
     * Flyttar spelaren till ett angränsande rum baserat på angiven riktning.
     *
     * @param string $direction
     * @return void
     */
    public function moveTo(string $direction): void
    {
        if (isset($this->rooms[$this->currentRoom][$direction])) {
            $this->currentRoom = $this->rooms[$this->currentRoom][$direction];
        }
    }
}