<?php 
// src/Form/PlayerUpdateType.php
namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlayerUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'player.label.name',
                'help' => 'help.characters',
                'mapped' => true,
                'required' => true,
                'attr' => [
                    'maxlength' => 24
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'player.label.password',
                'help' => 'help.characters',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'maxlength' => 24
                ]
            ])
            ->add('confirm', PasswordType::class, [
                'label' => 'player.label.confirm',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'maxlength' => 24
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'player.label.email',
                'help' => 'help.email',
                'mapped' => true,
                'required' => true,
                'attr' => [
                    'maxlength' => 128
                ]
            ])
            ->add('update', SubmitType::class, [
                'label' => 'player.label.update',
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}