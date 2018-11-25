<?php

namespace CMS\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

class InstallSelectLangType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language','choice',[
                'choices' => [
                'yes' => 1,
                'no' => 2,
                'maybe' => 3,
                ]
            ])->add('submit', 'submit');
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
        return 'cms_install_select_lang';
    }
}
