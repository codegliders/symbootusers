<?php

namespace Codegen\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Component\Validator\Constraints\Email;

//use Doctrine\Common\Collections\Collection;

class RoleType extends AbstractType {

    public function getName() {
        return 'formadd';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
           $builder
                ->add('name', 'text', array(
                    'attr' => array(
                        'placeholder' => 'Nome gruppo...',
                         'class' => 'form-control'
                    )
                ))
                ->add('description', 'text', array(
                    'attr' => array(
                        'placeholder' => 'Descrizione gruppo...',
                         'class' => 'form-control'
                    )
                ))
                ->add('accessLevel', 'choice', array(
                    'choices' => array('1' => '1 : Amministratore', '2' => '2 : Utente', '3' => '3 : Ospite'),
                     'attr' => array(
                         'class' => 'form-control'
                    )
                    
                ))
                ->add('cancel', 'reset', array(
                    'label'=>'Annulla',
                     'attr' => array(
                        
                         'class' => 'btn btn-default',
                          'data-dismiss' => 'modal'
                    )
                ))
                ->add('save', 'submit', array(
                    'label'=>'Aggiungi gruppo',
                     'attr' => array(
                        
                         'class' => 'btn btn-primary',
                         
                    )
                ));
    }

      public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
        'data_class' => 'Codegen\UserBundle\Entity\Role'
            ));
    }

}
