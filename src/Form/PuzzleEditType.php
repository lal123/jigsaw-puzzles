<?php 
// src/Form/PuzzleEditType.php
namespace App\Form;

use App\Entity\Puzzle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PuzzleEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keywords', TextareaType::class, [
                'help' => "Mots-clés du puzzle",
                'mapped' => true,
                'required' => true,
                'attr' => [
                    'maxlength' => 128
                ]
            ])
            ->add('update', SubmitType::class, [
                'label' => 'puzzle.edit.update.label',
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