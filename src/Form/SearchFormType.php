<?php


namespace App\Form;

use App\Data\Search;
use App\Data\SearchData;
use App\Entity\Products;
use App\Entity\Categories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\ProductsRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
class SearchFormType extends AbstractType
{



    public function configureOptions(OptionsResolver $resolver){

        $resolver->setDefaults([
                'data_class' => Search::class,
                'method'     => 'GET',
                'csrf_protection' => false,
                'abonnement'=>null
             

        ]);
    }

    public function fetBlockPrefix(){

        return '';
    }


    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
        $builder
        ->add('q', TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Duree'
            ]
        ])
        ->add('p', TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Prix'
            ]
        ])


        ;


    }
}