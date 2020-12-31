<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TchatBanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tchat_ban_counter', IntegerType::class,[
                'attr' => array(
                    'placeholder' => 'Chat ban (in minutes)'
                ),
            ])//Est en réalité une date en base de données, mais on utilise ça pour mettre des secondes puis convertir ensuite en date. Ce formulaire n'est pas lié à la base de données car on utilisera ajax pour envoyer les données vers un controlleur.
            ->add('steamId', TextType::class, [                
            ]);
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
