<?php

namespace CMS\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class MenuItemsType extends AbstractType
{	
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text',array('required' => false))
			->add('columns','choice',array('choices' => array('1' => '1','2' => '2','3' => '3')))
			->add('language','text',array('disabled' => true))
			->add('menu','entity',array('class' => 'CMS\ContentBundle\Entity\Menus', 'attr' => array('class' => 'hidden')))
			;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\ContentBundle\Entity\MenuItems'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'title';
    }
}
