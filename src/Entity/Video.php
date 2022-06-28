<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
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

    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'videos')]
    #[ORM\JoinColumn(nullable: false)]
    private Trick $trick;

    //CONSTRUCTOR
    public function __construct(string $url, Trick $trick)
    {
        $this->url = $url;
        $this->publishAt =  new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->trick = $trick;
    }

    //GETTERS AND SETTERS
    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPublishAt(): \DateTimeImmutable
    {
        return $this->publishAt;
    }

    public function getTrick(): Trick
    {
        return $this->trick;
    }

    public function setTrick(Trick $trick): void
    {
        $this->trick = $trick;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }


}
