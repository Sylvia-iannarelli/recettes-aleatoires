<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                "label" => "Votre commentaire",
                "attr" => [
                    "placeholder" => "Donnez-nous votre avis sur cette recette ici..."
                ]
            ])
            ->add('rating', ChoiceType::class, [
                "label" => "Votre note*",
                "choices" => [
                    "Excellent" => 5,
                    "Très bon" => 4,
                    "Plutôt bon" => 3,
                    "Bof" => 2,
                    "Pas deux fois..." => 1
                ],
                "expanded" => true,
                "multiple" => false,
                "help" => "* une seule réponse possible"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
