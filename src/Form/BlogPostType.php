<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('id', HiddenType::class, [
            'required'   => false,
        ])
        ->add('title', TextType::class, [
            'label' => 'Title',
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('body', TextareaType::class, [
            'attr' => ['rows' => '20'],
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('category', ChoiceType::class, [
            'choices'  => $options['categories'],
            'mapped' => false,
            'expanded' => false,
            'multiple' => false,
        ])
        ->add('author', TextType::class, [
            'attr' => [
                'readonly' => true,
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'post_token',
            'categories' => null,
        ]);
    }
}
