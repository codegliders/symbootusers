<?php

namespace Codegen\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserEditBsType extends AbstractType {

    public function getName() {
        return 'useredit';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                
                ->add('firstname', 'text', array(
                    'attr' => array(
                        'title'=>'Compila il campo nome',
                        'placeholder' => 'Nome...',
                        'class' => 'form-control',
                        'oninvalid' => "setCustomValidity('Il campo nome non può essere vuoto')",
                        //'onfocus' => "setCustomValidity('Il campo nome non può essere vuoto')",
                        'required'=> true
                    )
                ))
                ->add('lastname', 'text', array(
                    'attr' => array(
                        'title'=>'Compila il campo cognome',
                        'placeholder' => 'Cognome...',
                        'class' => 'form-control',
                        'oninvalid' => "setCustomValidity('Il campo cognome non può essere vuoto')",
                       // 'onfocus' => "setCustomValidity('Il campo cognome non può essere vuoto')",
                    )
                ))
                ->add('username', 'text', array(
                       
                    'attr' => array(
                        'title'=>'Compila il campo login',
                          'placeholder' => 'Login',
                        'class' => 'form-control',
                        'oninvalid' => "setCustomValidity('Il campo nome utente non può essere vuoto')",
                        //'onfocus' => "setCustomValidity('Il campo nome utente non può essere vuoto')",
                    )
                ))
                
//                ->add('password', 'password', array(
//                   'disabled'=>true,
//                    'required'=>false,
//                     'attr' => array(
//                           'placeholder' => 'Password',
//                        'class' => 'form-control',
//                    )
//                ))
                
                ->add('email', 'text', array(
                     'attr' => array(
                         'title'=>'Compila il campo email',
                           'placeholder' => 'Email',
                        'class' => 'form-control',
                           'oninvalid' => "setCustomValidity('Inserisci un indirizzo email valido')",
                       // 'onfocus' => "setCustomValidity('Inserisci un indirizzo email valido')",
                    )
                ))
                ->add('role', 'entity', array(
                 
                    'class' => 'Codegen\UserBundle\Entity\Role',
                    'property' => 'description',
                     'required' => false,
                    'empty_value'=>'Seleziona ruolo',
                     'attr' => array(
                           'title'=>"Seleziona al ruolo dell'utente",
                        'class' => 'form-control',
                    )
              
                ))
                ->add('groups', 'entity', array(
                    'class' => 'Codegen\UserBundle\Entity\Group',
                    'property' => 'description',
                    'multiple' => true,
                    'required' => false,
                ))
                ->add('isActive', 'checkbox', array(
                    'required' => false,
                      'attr' => array(
                        'class' => 'form-control',
                        'title'=>"Abilita o disabilita l'utente",
                    )
                ))
                ->add('cancel', 'reset', array(
                     'label'=>'Annulla',
                    'attr' => array(
                        'class' => 'btn btn-default',
                        'data-dismiss'=>'modal',
                       
                    )
                ))
                ->add('save', 'submit', array(
                    'label'=>'Conferma',
                     'attr' => array(
                        'class' => 'btn btn-primary',
                         
                    )
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Codegen\UserBundle\Entity\User'
        ));
    }

}
