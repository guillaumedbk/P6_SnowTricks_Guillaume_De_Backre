<?php

namespace App\Manager;

use App\Entity\Image;
use App\Entity\Trick;

class ImageFileManager
{
    protected $imageDirectory;

    public function __construct($imageDirectory)
    {
        $this->imageDirectory = $imageDirectory;
    }

    public function uploadFile(array $images, Trick $trick)
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