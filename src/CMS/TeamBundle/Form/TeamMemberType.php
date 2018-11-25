<?php

namespace CMS\TeamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamMemberType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('creator')
            ->add('lastName')
            ->add('firstName')
            ->add('street')
            ->add('number')
            ->add('zip')
            ->add('city')
            ->add('file', 'file')
            ->add('email')
            ->add('teamNumber')
            ->add('nationality')
            ->add('membershipPaid')
            ->add('birthDate','birthday')
            ->add('team', 'entity', [
                'class' => 'CMS\TeamBundle\Entity\Team',
                'choice_label' => 'teamName'
            ])
            ->add('role', 'entity', [
                'class' => 'CMS\TeamBundle\Entity\Role',
                'choice_label' => 'roleName'
            ])
            //            remove
            ->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\TeamBundle\Entity\TeamMember'
        ));
    }
}
