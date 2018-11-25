<?php

namespace CMS\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuKeyType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('MenuItems', 'collection', array('type' => new MenuItemsType()))
			->add('parentItem','entity', array('label' => 'Parent', 'class' => 'CMS\ContentBundle\Entity\MenuKey',))
			->add('save', 'submit');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\ContentBundle\Entity\MenuKey'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_contentbundle_menuKey';
    }
}
