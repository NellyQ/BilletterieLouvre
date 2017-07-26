<?php

namespace Louvre\BilletterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class DetailsType extends AbstractType
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
            ->add('visitorCountry', ChoiceType::class, array(
                'label' => 'Pays',
                'choices' => array('France' => 'France', 'Allemagne' => 'Allemagne'),
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
            'data_class' => 'Louvre\BilletterieBundle\Entity\Details'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'details';
    }


}