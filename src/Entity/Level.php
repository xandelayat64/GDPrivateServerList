<?php

namespace App\Entity;

use App\Repository\LevelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LevelRepository::class)]
class Level
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /*
    #[Assert\Length(
        min: 0,
        max: 20,
        maxMessage: "Level name can't be greater than 20 characters"
    )]
    */
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $place = null;

    #[ORM\Column(length: 255)]
    /*
    #[Assert\Length(
        min: 0,
        max: 20,
        maxMessage: "Creator name can't be greater than 20 characters"
    )]
    */
    private ?string $creator = null;

    #[ORM\Column(nullable: true)]
    private ?int $points = null;

    #[ORM\Column(length: 255)]
    private ?string $video = null;

    /*
    #[ORM\ManyToOne(inversedBy: 'completions')]
    private ?Player $player = null;
    */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(int $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getCreator(): ?string
    {
        return $this->creator;
    }

    public function setCreator(string $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): static
    {
        $this->video = $video;

        return $this;
    }

    public function getCompletions(): ?Player
    {
        return $this->completions;
    }

    public function setCompletions(?Player $completions): static
    {
        $this->completions = $completions;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }
}
