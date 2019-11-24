<?php 
// src/Form/PuzzleCreateType.php
namespace App\Form;

use App\Entity\Puzzle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PuzzleCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'help' => "Le titre du puzzle",
            ])
            ->add('keywords', TextareaType::class, [
                'help' => "Mots-clés du puzzle",
            ])
            ->add('update', SubmitType::class, [
                'label' => 'player.label.create',
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Puzzle::class,
        ]);
    }
}