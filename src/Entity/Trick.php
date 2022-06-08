<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TricksRepository::class)]
#[UniqueEntity('title')]
class Trick
{
    //ATTRIBUTES
        //id
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

        //title
    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

        //description
    #[ORM\Column(type: 'text', nullable: true)]
    private string $description;

        //imageUrl
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $imageUrl;

    #[ORM\OneToMany(mappedBy: 'trickId', targetEntity: Chat::class, orphanRemoval: true)]
    /**
     * @Collection<int, Chat>
     */
    private Collection $chats;

    //CONSTRUCTOR
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    //GETTERS AND SETTER
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return iterable<Chat>
     */
    public function getChats(): iterable
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setTrickId($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getTrickId() === $this) {
                $chat->setTrickId(null);
            }
        }

        return $this;
    }
}
