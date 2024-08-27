<?php

namespace App\Form;

use App\Entity\Reservationpack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class StatsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('annees',ChoiceType::class,[
                'placeholder' => 'Choisir une année'
               ])
        ;
        
        $builder->get('annees')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event)
            {
            $form = $event->getForm();
            $form->$event->getData();
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
