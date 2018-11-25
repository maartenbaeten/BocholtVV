<?php

namespace CMS\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

class AddTagsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tags','entity',array(
                'class' => 'CMS\ContentBundle\Entity\Tags',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.tagName', 'ASC');
                },
                'required' => true, 'multiple' => true,
            ))
            ->add('save', 'submit');
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
        return 'cms_contentbundle_contenttag';
    }
}
