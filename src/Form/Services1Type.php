<?php

namespace App\Form;

use App\Entity\Packs;
use App\Entity\Services;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Espaceprestatairee;

class Services1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description', TextareaType::class, [
                'attr' => array('cols' => '5', 'rows' => '5')
            ])

            ->add('prix')

            ->add('image', FileType::class, [
                'empty_data' => 'null',
                'data_class' => null,


            ])
            ->add('promo')

            ->add('categorie', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    "Mariée" => "Mariée",
                    "Marié" => "Marié",
                    "Bijoux" => "Bijoux",
                    "Traiteur" => "Traiteur",
                    "Décoration" => "Décoration",
                ],
            ])
            ->add('idprest',EntityType::class,[
                'class'=>Espaceprestatairee::class,
                
            ])

            ->add('idpack', EntityType::class, [
                'class' => Packs::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
            ])
            ->add('pos1')
            ->add('pos2');
            
    }
}