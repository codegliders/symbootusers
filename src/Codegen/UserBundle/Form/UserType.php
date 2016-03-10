<?php

namespace Codegen\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

//use Doctrine\Common\Collections\Collection;

class UserType extends AbstractType {

    public function getName() {
        return 'userform';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('firstname', 'text', array(
                    'attr' => array(
                        'placeholder' => 'Nome...',
                           'tabindex' => 1,
                        'class' => 'form-control',
                        'title' => 'Compila il campo nome',
                        'oninvalid' => "setCustomValidity('Il campo nome non può essere vuoto')",
                       // 'onfocus' => "setCustomValidity('')",
                        'required' => true
                    )
                ))
                ->add('lastname', 'text', array(
                    'attr' => array(
                          'tabindex' => 2,
                        'placeholder' => 'Cognome...',
                        'title' => 'Compila il campo cognome',
                        'class' => 'form-control',
                        'oninvalid' => 'setCustomValidity("Il campo cognome non può essere vuoto")',
                       // 'onfocus' => 'setCustomValidity("Il campo cognome non può essere vuoto")',
                        //'required' => true
                    )
                ))
                ->add('username', 'text', array(
                    'attr' => array(
                          'tabindex' => 3,
                        'placeholder' => 'Login...',
                        'title' => 'Compila il campo nome login',
                        'class' => 'form-control',
                        'oninvalid' => "setCustomValidity('Il campo nome login non può essere vuoto')",
                       // 'onfocus' => "setCustomValidity('Compila il campo nome login')",
                        //'required' => true
                    )
                ))
                ->add('email', 'email', array(
                    'attr' => array(
                          'tabindex' => 4,
                        'placeholder' => 'Email...',
                        'class' => 'form-control',
                        'title' => 'Compila il campo email',
                        'oninvalid' => 'setCustomValidity("Inserisci un indirizzo email valido.")',
                       //'onfocus' => 'setCustomValidity("Inserisci un indirizzo email valido.")',
                    )
                ))
                
                ->add('password', 'password', array(
                    'attr' => array(
                          'tabindex' => 5,
                        'placeholder' => 'Password...',
                        'class' => 'form-control',
                        'title' => 'Compila il campo password',
                        'oninvalid' => "setCustomValidity('Il campo password non può essere vuoto')",
                        //'onfocus' => "setCustomValidity('Il campo password non può essere vuoto')",
                        'required' => true
                    )
                ))
                ->add('role', 'entity', array(
                    'class' => 'Codegen\UserBundle\Entity\Role',
                    'property' => 'description',
                     'required' => false,
                    'empty_value'=>'Seleziona ruolo',
                     'attr' => array(
                           'tabindex' => 6,
                        'title' => "Seleziona il ruolo dell'utente",
                        'class' => 'form-control',
                        'oninvalid' => "setCustomValidity('Seleziona il ruolo utente)",
                        //'onfocus' => "setCustomValidity('Seleziona il ruolo utente')",
                        'required' => true
                    )
                    //'preferred_choices'=>array('Seleziona opzioni'=>'Seleziona opzioni'),
                ))
                
                ->add('groups', 'entity', array(
                    'class' => 'Codegen\UserBundle\Entity\Group',
                    'property' => 'description',
                    'required' => false,
                     'multiple' => true,
                    'empty_value'=>'Seleziona opzioni',
                    ##'preferred_choices'=>array('Utenti'=>'Utenti'),
                  
                    'attr' => array(
                        'oninvalid' => "setCustomValidity('Seleziona gruppo')",
                        //'onfocus' => "setCustomValidity('')"
                    )
                ))
                ->add('isActive', 'checkbox', array(
                    
                    'required' => false,
                    'attr' => array(
                          'tabindex' => 7,
                        'oninvalid' => "setCustomValidity('Seleziona gruppo')",
                       // 'onfocus' => "setCustomValidity('')",
                        'title' => "Abilita l'attività dell'utente",
                    )
                    ))
                
                ->add('cancel', 'reset', array(
                    'label' => 'Annulla',
                    'attr' => array(
                        'class' => 'btn btn-default',
                        'data-dismiss' => 'modal')))
                ->add('save', 'submit', array(
                    'label' => 'Salva utente',
                    'attr' => array(
                        'class' => 'btn btn-primary',
                    )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Codegen\UserBundle\Entity\User'
        ));
        $constraintCollection = new Collection(array(
            'username' => new NotBlank(array("message" => "Please fill out Name field")),
            'email' => array(new Email(array("message" => "Invalid Email", "checkMX" => true)),
                new NotBlank(array("message" => "Please fill out Email field"))),
            'password' => new NotBlank(array("message" => "Please fill out Message field"))
        ));

        return array(
            "validation_constraint" => $constraintCollection,
        );
    }

}
