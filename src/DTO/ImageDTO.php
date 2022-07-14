<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ImageDTO
{
    #[Assert\File]
    #[Assert\All(constraints: [
        new Assert\Type(UploadedFile::class)
    ])]
    public $file = null;
}