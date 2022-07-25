<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class TrickDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'The figure title must be at least {{ limit }} characters long',
        maxMessage: 'Your figure title cannot be longer than {{ limit }} characters',
    )]
    public $title;
    public $description;

    #[Assert\All(constraints: [
        new Assert\Type(VideoDTO::class)
    ])]
    public array $videoUrl;

    public ?ImageDTO $images = null;
}
