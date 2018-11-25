<?php

namespace CMS\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoriesType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contentItems', 'collection', array(
                'type' => new EditContentType(),
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
            ->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\ContentBundle\Entity\Categories'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_contentbundle_categories';
    }
}
