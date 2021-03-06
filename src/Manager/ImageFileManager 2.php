<?php

namespace App\Manager;

use App\Entity\Image;
use App\Entity\Trick;

class ImageFileManager
{
    private string $imageDirectory;

    public function __construct(string $imageDirectory)
    {
        $this->imageDirectory = $imageDirectory;
    }

    /**
     * @param iterable<Image> $images
     */
    public function uploadFile(iterable $images, Trick $trick)
    {
        //UPLOAD AND ADD ALL IMAGES
        foreach ($images as $image){
            //NEW FILE NAME
            $fileName = md5(uniqid()) . '.' . $image->guessExtension();
            //COPY IN UPLOAD DIR
            $image->move($this->imageDirectory, $fileName);
            //NEW IMAGE
            $newImg = new Image($fileName, $trick);
            //ADD IMAGE TO THE TRICK
            $trick->addImage($newImg);
        }

    }
}
