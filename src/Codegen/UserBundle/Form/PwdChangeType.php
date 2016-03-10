<?php

namespace Codegen\UserBundle\Form;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PwdChangeType extends AbstractType {

    public function getName() {
        return 'userform';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('currentpassword', 'password', array('label'=>'Password attuale', 'mapped' => false, 'constraints' => new UserPassword(),'attr' => array('placeholder' => 'Password attuale')));
       
        $builder->add('password', 'repeated', array(
        'type' => 'password',
        'invalid_message' => 'Le due password devono essere uguali.',
        'required' => false,
        'first_options' => array('label' => 'Nuova Password', 'attr' => array('placeholder' => 'Nuova Password')),
        'second_options' => array('label' => 'Ripeti Password', 'attr' => array('placeholder' => 'Ripeti password')),
        ));
       
        $builder->add('cancel', 'reset');
           $builder->add('save', 'submit');
           
    }

}
