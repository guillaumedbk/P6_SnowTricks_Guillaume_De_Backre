<?php

namespace App\Form;

use App\DTO\TrickDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateTrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => array('placeholder' => 'Trick title'),
                'required' => true
            ])
            ->add('description', TextType::class, [
                'attr' => array('placeholder' => 'Trick description'),
                'required' => false
            ])
            ->add('imageUrl', UrlType::class, [
                'attr' => array('placeholder' => 'ex: https://cdn.pixabay.com/snowboard.jpg'),
                'required' => false
            ])
            ->add('videoUrl', UrlType::class, [
                'attr' => array('placeholder' => 'ex: https://www.youtube.com/embed/'),
                'required' => false
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => true,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrickDTO::class,
        ]);
    }
}
