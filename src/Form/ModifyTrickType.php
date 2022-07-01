<?php

namespace App\Form;

use App\DTO\TrickDTO;
use App\Entity\Trick;
use App\Repository\TricksRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyTrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $trick = $options['trick'];

        $builder
            ->add('title', TextType::class, [
                'attr' => array('value' => $trick->getTitle()),
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'data' => $trick->getDescription(),
                'required' => false,
            ])
            ->add('imageUrl', UrlType::class, [
                'attr' => array('value' => $trick->getImageUrl()),
                'required' => false
            ])
            ->add('videoUrl', UrlType::class, [
                'required' => false
            ])

            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrickDTO::class,
            $resolver->setRequired(['trick'])
        ]);
    }
}
