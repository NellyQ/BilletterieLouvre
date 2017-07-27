<?php

namespace Louvre\BilletterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;


class GlobalType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('details', CollectionType::class, [
                'entry_type'         => DetailType::class,
                'allow_add'    => true,
                'allow_delete' => true
            ])
            ->add('Valider', SubmitType::class);
    }
}