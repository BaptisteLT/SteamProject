<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('profileName')
            ->add('steamId')
            ->add('tchat_ban')
            ->add('site_ban')
            ->add('roles',ChoiceType::class, [ /*J'ai repris du projet a2k*/
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Moderator' => 'ROLE_MOD',
                    'Administrator' => 'ROLE_ADMIN',
                ],
                'multiple'=>true,
                'expanded'=>false,
                'label'=>'Roles'
            ],
        )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
