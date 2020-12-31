<?php

namespace App\Form;

use App\Entity\Craft;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CraftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class, array(
                'attr' => array(
                     'placeholder' => 'Title'
                 )
            ))
            ->add('description', TextareaType::class, array(
                'attr' => array(
                     'placeholder' => 'Description (can stay empty)'
                ),
                'required'=>false,
            ))
            ->add('cost', NumberType::class, array(
                'attr' => array(
                     'placeholder' => 'Initial cost in USD (can stay empty)'
                ),
                'required'=>false,
            ))
            ->add('imageFile', VichImageType::class)
            /*->add('nom_image')*/

            ->add('Slot1')
            ->add('Slot2')
            ->add('Slot3')
            ->add('Slot4')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Craft::class,
        ]);
    }
}
