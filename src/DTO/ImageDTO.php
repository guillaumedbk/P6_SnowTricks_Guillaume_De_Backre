<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ImageDTO
{
    public $id = null;

    public $file = null;

    #[Assert\Url]
    public $url = null;

}