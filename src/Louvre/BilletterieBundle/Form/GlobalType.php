<?php

namespace Louvre\BilletterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;


class GlobalType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('details', CollectionType::class, [
                'label' => " ",
                'entry_type' => DetailType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,

            ])
            
            ->add('commandePrixTotal', IntegerType::class, array(
                'label' => " ",
                'attr' => array (
                    'readonly' => true,
                ),
                'required' => true));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\BilletterieBundle\Entity\Commande'
        ));
    }


    public function getName()
        {
            return 'global';
        }
}