<?php

namespace CMS\TeamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('creator')
            ->add('teamName')
            ->add('teamDescription', 'textarea')
            ->add('file', 'file')
            ->add('ordering')
            ->add('showRanking')
            ->add('category', 'entity', [
                'class' => 'CMS\TeamBundle\Entity\Category',
                'choice_label' => 'name'
            ])
            ->add('classification','collection',[
                'type' => new ClassificationType(),
                'allow_add' => true,
                'allow_delete' => true
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
            'data_class' => 'CMS\TeamBundle\Entity\Team'
        ));
    }
}
