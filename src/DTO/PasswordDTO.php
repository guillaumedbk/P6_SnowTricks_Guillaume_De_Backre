<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordDTO
{
    #[Assert\NotBlank]
    public $password;
}
