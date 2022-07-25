<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class VideoDTO
{
    #[Assert\Url]
    public $url;
}
