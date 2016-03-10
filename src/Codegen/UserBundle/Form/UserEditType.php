<?php

namespace Codegen\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserEditType extends AbstractType {

    public function getName() {
        return 'userform';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                
                 
                ->add('username', 'text',array(
                    'attr' => array(
                        'placeholder' => 'Nome utente',
                           
                    )
                ))
                
              
                
                ->add('email', 'text')
                ->add('role', 'entity', array(
                    'class'     => 'Codegen\UserBundle\Entity\Role',
                    'property'  => 'description',
                        
                ))
                  ->add('groups', 'entity', array(
                    'class'     => 'Codegen\UserBundle\Entity\Group',
                    'property'  => 'description',
                    'multiple' => true,
                    'required' => false,
                         
                ))
                ->add('isActive', 'checkbox',array(
                        'required' => false,
                        
                    ))
                ->add('cancel', 'reset',array(
                        
                    ))
                ->add('save', 'submit',array(
                        
                    ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
        'data_class' => 'Codegen\UserBundle\Entity\User'
            ));
    }

}
