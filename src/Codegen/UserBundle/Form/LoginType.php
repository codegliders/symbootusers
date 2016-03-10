<?php

namespace Codegen\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;


//use Doctrine\Common\Collections\Collection;

class LoginType extends AbstractType {

    public function getName() {
        return null;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('_username', 'text', array(
                    'attr' => array(
                        'placeholder' => 'Nome utente...',
                        'class' => 'form-control',
                        'oninvalid' => "setCustomValidity('Il campo nome utente non può essere vuoto')",
                      //  'onfocus' => "setCustomValidity('Compila il campo nume utente')",
                        'title' => 'Compila il campo username',
                       
                    )
                ))
          
                ->add('_password', 'password', array(
                    'attr' => array(
                        'placeholder' => 'Password...',
                        'class' => 'form-control',
                         'title' => 'Compila il campo password',
                        'oninvalid' => "setCustomValidity('Il campo password non può essere vuoto')",
                        //'onfocus' => "setCustomValidity('Compila il campo password')",
                      
                    )
                ))
               
               
              
                ->add('login', 'submit', array(
                    'label' => 'Login',
                    'attr' => array(
                        'class' => 'btn btn-primary',
                         'style' => 'width:100%',
        )
                    ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Codegen\UserBundle\Entity\User'
        ));
        $constraintCollection = new Collection(array(
            'username' => new NotBlank(array("message" => "Compila il campo Username...")),
    
            'password' => new NotBlank(array("message" => "Compila il campo Password..."))
        ));

        return array(
            "validation_constraint" => $constraintCollection,
        );
    }
    
    
}
