<?php

namespace Codegen\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RoleAddType extends AbstractType {

    public function getName() {
        return 'roleform';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
         $builder
                ->add('name', 'text', array(
                    'attr' => array(
                        'placeholder' => 'Nome ruolo'
                    )
                ))
                ->add('description', 'text', array(
                    'attr' => array(
                        'placeholder' => 'Descrizione ruolo'
                    )
                ))
                ->add('accessLevel', 'choice', array(
                    'choices' => array('1' => '1 : Amministratore', '2' => '2 : Utente', '3' => '3 : Ospite'),
                ))
                ->add('cancel', 'reset')
                ->add('save', 'submit');
         }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
        'data_class' => 'Codegen\UserBundle\Entity\Role'
            ));
    }

}
