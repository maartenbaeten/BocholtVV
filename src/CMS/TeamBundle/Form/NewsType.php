<?php

namespace CMS\TeamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('file', 'file')
            ->add('team', 'entity', [
                'class' => 'CMS\TeamBundle\Entity\Team',
                'choice_label' => 'teamName'
            ])
            ->add('attachments', 'collection', [
                'type' => new NewsAttachmentType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'error_bubbling' => false,
                'cascade_validation' => true,
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
            'data_class' => 'CMS\TeamBundle\Entity\News'
        ));
    }
}
