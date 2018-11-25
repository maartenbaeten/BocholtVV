<?php

namespace CMS\CareerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResumeWideType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',array('attr' => array('class' => 'form-control', 'placeholder' => 'contact.name')))
            ->add('email','text',array('attr' => array('class' => 'form-control', 'placeholder' => 'contact.email')))
            ->add('dreamjob','text',array('attr' => array('class' => 'form-control', 'placeholder' => 'contact.subject')))
            ->add('description','textarea',array('attr' => array('class' => 'form-control messageheight', 'placeholder' => 'contact.message')))
            ->add('file')
            ->add('save', 'submit',array('attr' => array('class' => 'btn btn-primary btn-fullwidth')));
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\CareerBundle\Entity\Resume'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_careerbundle_resume';
    }
}
