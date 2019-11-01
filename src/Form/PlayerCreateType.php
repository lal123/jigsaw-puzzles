<?php 
// src/Form/PlayerType.php
namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlayerCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'player.label.name',
                'help' => 'help.characters',
                'required' => true,
                'attr' => [
                    'maxlength' => 24
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'player.label.password',
                'help' => 'help.characters',
                'required' => true,
                'attr' => [
                    'maxlength' => 24
                ]
            ])
            ->add('create', SubmitType::class, [
                'label' => 'player.label.create'
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