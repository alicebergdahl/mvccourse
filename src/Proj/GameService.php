<?php

namespace App\Proj;

class GameService
{
    private $currentRoom = 1;
    private $rooms = [
        1 => ['east' => 2],
        2 => ['east' => 3, 'west' => 1, 'south' => 4],
        3 => ['west' => 2],
        4 => ['north' => 2, 'east' => 5],
        5 => [],
    ];

    public function getCurrentRoom(): int
    {
        return $this->currentRoom;
    }

    public function setCurrentRoom(int $room): void
    {
        $this->currentRoom = $room;
    }

    public function getAvailableDirections(): array
    {
        return array_keys($this->rooms[$this->currentRoom]);
    }

    public function moveTo(string $direction): void
    {
        if (isset($this->rooms[$this->currentRoom][$direction])) {
            $this->currentRoom = $this->rooms[$this->currentRoom][$direction];
        }
    }
}