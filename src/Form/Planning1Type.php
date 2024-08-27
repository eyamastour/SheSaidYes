<?php

namespace App\Form;

use App\Entity\Planning;
use App\Entity\Espaceprestatairee;
use App\Entity\Services;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class Planning1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateplan')
            ->add('etatplan')
            ->add('idservice', EntityType::class, [
                'class' => Services::class,
            ])
            ->add('idprest', EntityType::class, [
                'class' => Espaceprestatairee::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}
