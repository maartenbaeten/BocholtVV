<?php

namespace CMS\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', 'choice', [
                'choices' => ['ROLE_SUPER_ADMIN' => 'Super admin', 'ROLE_TRAINER' => 'Trainer'],
            ])
            ->add('save', 'submit')
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
            // I need to get first role for edit (second role is always ROLE_USER)
                function ($array) {
                    $result = null;
                    if($array) {
                        $result = $array[0];
                    }
                    return $result;
                },
                // transform the string to an array
                function ($string) {
                    $result = [$string];
                    return $result;
                }
            ))
        ;

    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\UserBundle\Entity\User'
        ));
    }
}
