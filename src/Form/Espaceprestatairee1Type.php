<?php

namespace App\Form;
use App\Entity\Utilisateur;

use App\Entity\Espaceprestatairee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Espaceprestatairee1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomsociete')
            ->add('numsociete')
            ->add('faxsociete')
            ->add('iduser',EntityType::class,[
                'class'=>Utilisateur::class,
               ])
              
            ->add('catsociete',ChoiceType::class ,['placeholder' =>'choisir une categorie' ,
            'choices' => [
                'Location' => 'location',
                'vente' => 'vente',
                'les deux ' => 'les deux '
                
            ], ])
            ->add('typesociete',ChoiceType::class ,['placeholder' =>'choisir une categorie' ,
            'choices' => [
                'service' => 'service',
                'bien' =>'bien',
                'les deux ' => 'les deux '
                
            ], ])
            ->add('logo', FileType::class, ['mapped' => false])
            
            ->add('url')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Espaceprestatairee::class,
        ]);
    }
    
}
