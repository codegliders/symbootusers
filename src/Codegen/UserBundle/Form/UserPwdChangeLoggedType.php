<?php

namespace Codegen\UserBundle\Form;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserPwdChangeLoggedType extends AbstractType {

    public function getName() {
        return 'pwdchangeloggedform';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('currentpassword', 'password', array('label' => 'Password attuale', 'mapped' => false, 'constraints' => new UserPassword(), 'attr' => array('placeholder' => 'Digita password attuale')));

        $builder->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'Le due password devono essere uguali...',
            'required' => false,
            'first_options' => array('label' => 'Nuova password', 'attr' => array('placeholder' => 'Digita nuova password...')),
            'second_options' => array('label' => 'Ripeti password', 'attr' => array('placeholder' => 'Ripeti nuova password')),
        ));

        $builder->add('cancel', 'reset', array(
            'attr' => array(
                'class' => 'btn btn-default',
         
        )));

        $builder->add('save', 'submit', array(
            'label' => 'Modifica password',
            'attr' => array(
                'class' => 'btn btn-primary',
                  
         
        )));
    }

}
