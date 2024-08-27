<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, ['mapped' => false])
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            // ->add('username', TextType::class)
            ->add('tel', NumberType::class)
            ->add('email', TextType::class)
            ->add('Submit', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-outline-success'
                )
            ))
          
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
