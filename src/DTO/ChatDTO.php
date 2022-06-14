<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ChatDTO
{
    #[Assert\NotBlank]
    public $content;
}
