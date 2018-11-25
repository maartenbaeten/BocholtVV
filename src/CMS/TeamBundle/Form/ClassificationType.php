<?php

namespace CMS\TeamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClassificationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position')
            ->add('teamName')
            ->add('M')
            ->add('W')
            ->add('G')
            ->add('V')
            ->add('F')
            ->add('A')
            ->add('GD')
            ->add('PTN')
            ->add('bocholtVV', 'checkbox')
        ;
    }
}
