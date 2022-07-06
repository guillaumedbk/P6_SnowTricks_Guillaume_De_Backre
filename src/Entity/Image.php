<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Cascade;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    //ATTRIBUTES
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private string $url;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $publishAt;

    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE" )]
    private Trick $trick;

    //CONSTRUCTOR
    public function __construct(string $url, Trick $trick)
    {
        $this->url = $url;
        $this->publishAt = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
    }

    //GETTERS AND SETTERS
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPublishAt(): \DateTimeInterface
    {
        return $this->publishAt;
    }

    public function getTrick(): Trick
    {
        return $this->trick;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setTrick(Trick $trick): void
    {
        $this->trick = $trick;
    }


}
