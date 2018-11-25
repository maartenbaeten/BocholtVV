<?php

namespace CMS\CareerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResumeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',array('attr' => array('class' => 'form-control', 'placeholder' => 'Naam')))
            ->add('email','text',array('attr' => array('class' => 'form-control', 'placeholder' => 'Email')))
            ->add('dreamjob','choice',array(
                'choices'  => array('Admin & Support' => 'Admin & Support', 'Consulting' => 'Consulting', 'Finance' => 'Finance', 'HR' => 'HR', 'IT Services' => 'IT Services', 'Legal Advisor' => 'Legal Advisor', 'Logistics Supply Chain' => 'Logistics Supply Chain', 'Marcom' => 'Marcom', 'Product Management' => 'Product Management', 'Project Management' => 'Project Management', 'Sales' => 'Sales', 'Webdevelopment' => 'Webdevelopment', 'Operations and Support' => 'Operations and Support'),
                'placeholder' => 'Kies Droomjob',
                'required' => false,
                'multiple' => true,
            ))
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
