<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Tag;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Validator\Constraints\File;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')

            ->add('preview', FileType::class, [
                'label' => 'Upload preview image',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])

            ->add('description')
            ->add('body', TextareaType::class)
            ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'name'])
            ->add('tags', EntityType::class, ['class' => Tag::class, 'choice_label' => 'name', 'multiple' => true])
            ->add('save', SubmitType::class, ['label' => 'Create Post'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
