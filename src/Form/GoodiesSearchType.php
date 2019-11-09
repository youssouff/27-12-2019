<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\GoodiesSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class GoodiesSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('maxPrice', IntegerType::class,[
            'required' => false,
            'label' => false,
            'attr' => [
                'placeholder' => 'Max price'
            ]
        ])
        ->add('orderBy', ChoiceType::class, [
            'choices' => [
                'Nom' => 'name',
                'Prix' => 'price'],
            'required' => false,
            'label' => false
        ])
        ->add('orderType', ChoiceType::class, [
            'choices' => [
                'Croissant' => 'ASC',
                'Décroissant' => 'DESC'],
            'required' => false,
            'label' => false
        ])
        ->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'name',
            'required' => false,
            'label' => false
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GoodiesSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix(){
        return '';
    }
}
