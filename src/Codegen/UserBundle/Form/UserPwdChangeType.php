<?php

namespace Codegen\UserBundle\Form;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserPwdChangeType extends AbstractType {

    public function getName() {
        return 'pwdchangeform';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('password', 'password', array(
            'label' => 'Password attuale',
            'required' => true,
            'attr' => array('placeholder' => 'Digita la nuova password...',
                'class' => 'form-control',
                'oninvalid' => "setCustomValidity('Compila il campo password')",
                'title' => 'Digita la nuova password',
        )));


        $builder->add('save', 'submit', array(
            'label' => 'Modifica password',
            'attr' => array(
                'class' => 'btn btn-danger',
                'data-dismiss' => 'modal'
        )));
    }

}
