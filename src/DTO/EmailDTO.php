<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EmailDTO
{
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank]
    public $email;
}
