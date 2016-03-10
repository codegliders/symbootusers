<?php

namespace Codegen\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserBsType extends AbstractType {

    public function getName() {
        return 'userform';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('firstname', 'text',array(
                    'attr' => array(
                        'placeholder' => 'Nome...',
                        'title'=>'Compila il campo nome',
                        'oninvalid' => "setCustomValidity('Il campo nome non può essere vuoto')",
                      
                        'cssClass' =>'form-control',
                       // 'required'=>true
                    )
                ))
                 ->add('lastname', 'text',array(
                       
                    'attr' => array(
                        'title'=>'Compila il campo cognome',
                         'oninvalid' => "setCustomValidity('Il campo cognomee non può essere vuoto')",
                      
                        'placeholder' => 'Cognome...',
                        'cssClass' =>'form-control',
                        'required'=>true
                    )
                ))
                ->add('username', 'text',array(
                       'oninvalid' => "setCustomValidity('Il campo nome utente non può essere vuoto')",
                     
                    'attr' => array(
                        'placeholder' => 'Nome utente...',
                        'cssClass' =>'form-control'
                    )
                ))
                ->add('password', 'password',array(
                    'attr' => array(
                        'placeholder' => 'Password...'
                    )))
                
                ->add('email', 'text',array(
                    'attr' => array(
                        'placeholder' =>'Email...'
                    )
                ))
                ->add('role', 'entity', array(
                    'class'     => 'Codegen\UserBundle\Entity\Role',
                    'property'  => 'description'
                ))
                 ->add('groups', 'entity', array(
                    'class'     => 'Codegen\UserBundle\Entity\Group',
                    'property'  => 'description',
                    'multiple' => true,
                     //'expanded' => true
                ))
                ->add('isActive', 'checkbox',  array('required' => false))
                
                ->add('cancel', 'reset')
                ->add('save', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
        'data_class' => 'Codegen\UserBundle\Entity\User'
            ));
    }

}
