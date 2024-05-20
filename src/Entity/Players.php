<?php

namespace App\Entity;

use App\Repository\PlayersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayersRepository::class)]
class Players
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $playername = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayername(): ?string
    {
        return $this->playername;
    }

    public function setPlayername(string $playername): static
    {
        $this->playername = $playername;

        return $this;
    }
}
