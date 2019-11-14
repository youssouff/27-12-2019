<?php

namespace App\Form;

use App\Entity\Center;
use App\Security\ApiUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ApiUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('name')
            ->add('firstName')
            ->add('telephone')
            ->add('campus', EntityType::class, [
                'class' => Center::class,
                'choice_label' => 'denomination',
                'choice_value' => function (Center $center){
                    return $center->getDenomination()
                }
            ])
            ->add('promotion')
            ->add('age')
            ->add('password', PasswordType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ApiUser::class,
        ]);
    }
}
