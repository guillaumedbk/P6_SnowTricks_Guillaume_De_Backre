<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
    private ?string $description = null;

        //chats liaison
    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Chat::class, orphanRemoval: true)]
    /**
     * @Collection<int, Chat>
     */
    private Collection $chats;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Video::class, orphanRemoval: true, cascade: ['persist'])]
    /**
     * @Collection<int, Video>
     */
    private Collection $videos;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $publishAt;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Image::class, cascade: ['persist', 'remove'])]
    /**
     * @Collection<int, Image>
     */
    private Collection $images;

    //CONSTRUCTOR
    public function __construct(string $title)
    {
        $this->title = $title;
        $this->publishAt = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->chats = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    //GETTERS AND SETTER
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
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
            if ($chat->getTrick() === $this) {
                $chat->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return iterable<Video>
     */
    public function getVideos(): iterable
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

    public function getPublishAt(): \DateTimeInterface
    {
        return $this->publishAt;
    }

    /**
     * @return iterable<Image>
     */
    public function getImages(): iterable
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setTrick($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getTrick() === $this) {
                $image->setTrick(null);
            }
        }

        return $this;
    }




}
