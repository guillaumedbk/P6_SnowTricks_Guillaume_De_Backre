<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ChatDTO
{
    public $content;

    /**
     * @var string A "Y-m-d H:i:s" formatted value
     */
    #[Assert\DateTime]
    public $publishAt;
}