<?php

namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'The firstname must be at least {{ limit }} characters long',
        maxMessage: 'The firstname cannot be longer than {{ limit }} characters',
    )]
    public $firstname;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'The name must be at least {{ limit }} characters long',
        maxMessage: 'The name cannot be longer than {{ limit }} characters',
    )]
    public $name;

    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    public $email;

    #[Assert\NotBlank]
    public $password;
}
