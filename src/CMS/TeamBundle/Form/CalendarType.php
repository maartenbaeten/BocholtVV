<?php

namespace CMS\TeamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('creator')
            ->add('date', 'datetime',
                ['widget' => 'single_text',
                    'html5' => false,
                    'format' => 'dd/MM/yyyy HH:mm',
                ])
            ->add('score')
            ->add('home')
            ->add('file', 'file')
            ->add('challengerName')
            ->add('team', 'entity', [
                'class' => 'CMS\TeamBundle\Entity\Team',
                'choice_label' => 'teamName'
            ])
            ->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\TeamBundle\Entity\Calendar'
        ));
    }
}
