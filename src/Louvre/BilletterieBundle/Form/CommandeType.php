<?php

namespace Louvre\BilletterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commandeDate', DateType::class, array(
                'label' => 'Date de votre visite',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => [
                'class' => ''
                ]
            ))
            ->add('commandeTypeBillet', ChoiceType::class, array(
                'label' => 'Type de billet',
                'choices' => array('Journée' => 'Journee', 'Demi-journée (accès au musée à partir de 14h00)' => 'Demi-journee'),
                'multiple'  => false,
                'expanded'  => true,)
            )
            ->add('commandeNbBillet', IntegerType::class, array(
                'label' => 'Nombre de billet',
                'data' => 1,
                'attr' => array('min'=> 1, 'max' => 10),)
                  
            );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\BilletterieBundle\Entity\Commande'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Commande';
    }


}
