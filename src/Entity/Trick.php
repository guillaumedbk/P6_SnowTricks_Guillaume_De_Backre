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
    #[ORM\Column(type: 'string', length: 255, unique: true)]
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

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Video::class, cascade: ['persist', 'remove'])]
    /**
     * @Collection<int, Video>
     */
    private Collection $videos;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $publishAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $lastModified = null;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Image::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: "CASCADE" )]
    /**
     * @Collection<int, Image>
     */
    private Collection $images;

    #[ORM\OneToOne(targetEntity: Image::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: "SET NULL" )]
    private ?Image $mainImage = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $slug;

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
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        $this->images->removeElement($image);
        if($this->mainImage === $image){
            $this->setMainImageWithFirstImage();
        }

        return $this;
    }

    public function getMainImage(): ?Image
    {
        return $this->mainImage;
    }

    public function setMainImage(?Image $mainImage): self
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    public function setMainImageWithFirstImage(): void
    {
        $this->mainImage = $this->images->first();
    }

    public function getLastModified(): ?\DateTime
    {
        return $this->lastModified;
    }

    public function setLastModified(\DateTime $lastModified): void
    {
        $this->lastModified = $lastModified;
    }

    public function newSlug(string $title): string
    {
        return mb_strtolower(preg_replace(array('/[^a-zA-Z0-9 \'-]/', '/[ -\']+/', '/^-|-$/'),
            array('', '-', ''), iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title)));
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(): self
    {
        $this->slug = $this->newSlug($this->title);
        return $this;
    }

}
