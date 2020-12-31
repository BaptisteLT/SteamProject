<?php

namespace App\Form;

use App\Entity\CraftSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CraftSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Title'
                    ]
                ]
            )
            ->add('cost_min', TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Minimal cost'
                    ]
                ]
            )
            ->add('cost_max', TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Maximal cost'
                    ]
                ]
            )
            ->add('date_min', TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Minimum date'
                    ]
                ]
            )
            ->add('date_max', TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Maximum date'
                    ]
                ]
            )
            ->add('slot1', TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Sticker slot 1'
                    ]
                ]
            )
            ->add('slot2', TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Sticker slot 2'
                    ]
                ]
            )
            ->add('slot3', TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Sticker slot 3'
                    ]
                ]
            )
            ->add('slot4', TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Sticker slot 4'
                    ]
                ]
            )
            ->add('ItemsGroup', TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Stickers category'
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CraftSearch::class,
            'method'=>'get',
        ]);
    }


    public function getBlockPrefix()
    {
        return '';
    }
}
