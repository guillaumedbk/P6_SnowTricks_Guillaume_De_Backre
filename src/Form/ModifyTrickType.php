<?php

namespace App\Form;

use App\DTO\TrickDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('videoUrl', CollectionType::class, [
                'entry_type' => VideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'label' => false
            ])

            ->add('images', ImageType::class, [
                'mapped' => true,
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
