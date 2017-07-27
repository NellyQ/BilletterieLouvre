<?php

namespace Louvre\BilletterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class DetailType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitorName', TextType::class, array(
                'label' => 'Nom'))
            ->add('visitorFisrtname', TextType::class, array(
                'label' => 'Prénom'))
            ->add('visitorAge', DateType::class, array(
                'label' => 'Date de naissance',
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y') -100, date('Y')),
                ))
            ->add('visitorCountry', CountryType::class, array(
                'label' => 'Pays',
                'placeholder' => 'Choisissez un pays',
            ))
            ->add('visitorReduc', CheckboxType::class, array(
                'label' => 'Tarif réduit',
                'required' => false));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\BilletterieBundle\Entity\Detail'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'detail';
    }


}
