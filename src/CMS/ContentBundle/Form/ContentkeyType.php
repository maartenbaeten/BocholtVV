<?php

namespace CMS\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentKeyType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('creationDate')
			->add('contentItems', 'collection', array('type' => new ContentType()))
            ->add('contentType', 'text')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\ContentBundle\Entity\ContentKey'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_contentbundle_contentKey';
    }
}
