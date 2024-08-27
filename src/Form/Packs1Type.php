<?php

namespace App\Form;

use App\Entity\Packs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class Packs1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description', TextareaType::class, [
                'attr' => array('cols' => '5', 'rows' => '5')
            ])
            ->add('prix')

            ->add('promo')
            ->add('image', FileType::class, [
                'empty_data' => 'null',
                'data_class' => null,


            ])
            ->add('pos1')
            ->add('pos2');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Packs::class,
        ]);
    }
}
