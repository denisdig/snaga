<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Produits;
use App\Entity\Commentaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('text', null, [
            'label' => false,
            'required' => false,
            'attr' => [
                'rows' => 4,
                'placeholder' => 'Donnez votre avis'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez écrire votre commentaire'
                ])
            ]
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaires::class,
        ]);
    }
}
