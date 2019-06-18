<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Entity\Tva;
use App\Form\ImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('prix')
            ->add('content')
            ->add('categorie', EntityType::class,[
                'class' => Categorie::class,
                'choice_label' => 'name'
            ])
            ->add('image', ImageType::class)
            ->add('tva', EntityType::class,[
                'class' => Tva::class,
                'choice_label' => 'nom'
            ])
           ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
