<?php

namespace App\DTO;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserAdminDTO extends UserDTO
{
    #[Assert\NotBlank]
    #[Assert\Choice([User::ADMIN, User::USER])]
    public $status;
}