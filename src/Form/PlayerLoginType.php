<?php 
// src/Form/PlayerLoginType.php
namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlayerLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'player.label.name',
                'mapped' => true,
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'player.label.password',
                'mapped' => true,
                'required' => true,
            ])
            /*
            ->add('remember_me', CheckboxType::class, [
                'label' => 'player.label.remember_me',
                'mapped' => false,
                'required' => false,
                'value' => 1,
            ])
            */
            ->add('create', SubmitType::class, [
                'label' => 'player.label.login',
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