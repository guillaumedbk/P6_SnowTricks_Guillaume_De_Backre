<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat
{
    //ATTRIBUTES
        //id
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

        //content
    #[ORM\Column(type: 'text')]
    private string $content;

        //date
    #[ORM\Column(type: 'datetime')]
    private \DateTime $publishAt;

    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'chats')]
    #[ORM\JoinColumn(nullable: false)]
    private Trick $trick;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'chats')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    //CONSTRUCTOR
    public function __construct(User $user, Trick $trick, \DateTimeInterface $publishAt, string $content)
    {
        $this->user = $user;
        $this->trick = $trick;
        $this->publishAt = $publishAt;
        $this->content = $content;
    }

    //GETTERS AND SETTERS
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getUser(): User
    {
        return $this->user;
    }

    public function getTrick(): Trick
    {
        return $this->trick;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getPublishAt(): ?\DateTimeInterface
    {
        return $this->publishAt;
    }

    public function setPublishAt(\DateTimeInterface $publishAt): self
    {
        $this->publishAt = $publishAt;

        return $this;
    }

    public function getTrickId(): ?Trick
    {
        return $this->trickId;
    }
}
