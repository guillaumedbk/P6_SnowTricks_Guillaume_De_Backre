<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email')]
class User
{
    //ATTRIBUTES
        //id
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

        //firstname
    #[ORM\Column(type: 'string', length: 255)]
    private string $firstname;

        //name
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

        //email
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

        //password
    #[ORM\Column(type: 'text')]
    private string $password;

        //status
    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Chat::class, orphanRemoval: true)]
    /**
     * @var Collection<int, Chat>
     */
    private Collection $chats;

    //CONSTRUCTOR
    public function __construct(string $email, string $password, string $firstname, string $name)
    {
        $this->email = $email;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->name = $name;
    }

    //GETTERS AND SETTERS
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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
            $chat->setUserId($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getUserId() === $this) {
                $chat->setUserId(null);
            }
        }

        return $this;
    }
}
