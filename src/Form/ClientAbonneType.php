<?php

namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\Coupon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClientAbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('duree' , ChoiceType::class ,array('choices'=>array('1 mois'=>'1 mois','3 mois'=>'3 mois','6 mois'=>'6 mois','12 mois'=>'12 mois')))
            //->add('coupon',EntityType::class,['class'=>Coupon::class,'choice_label'=>'coupons','multiple'=>true,'expanded'=>true])
 
            ->add('passer au paiement',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}
