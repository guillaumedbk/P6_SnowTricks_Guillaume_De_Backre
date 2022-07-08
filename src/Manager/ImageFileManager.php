<?php

namespace App\Manager;

use App\Entity\Image;
use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageFileManager extends AbstractController
{
    public function uploadFile(array $images, Trick $trick)
    {
        //UPLOAD AND ADD ALL IMAGES
        foreach ($images as $image){
            //NEW FILE NAME
            $file = md5(uniqid()) . '.' . $image->guessExtension();
            //COPY IN UPLOAD DIR
            $image->move($this->getParameter('images_directory'), $file);
            //NEW IMAGE
            $newImg = new Image($file, $trick);
            //ADD IMAGE TO THE TRICK
            $trick->addImage($newImg);
        }

    }

}