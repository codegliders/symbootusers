<?php

namespace Codegen\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupEditType extends AbstractType {

    public function getName() {
        return 'groupform';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', 'text', array(
                    'attr' => array(
                        'placeholder' => 'Nome gruppo'
                    )
                ))
                ->add('description', 'text', array(
                    'attr' => array(
                        'placeholder' => 'Descrizione gruppo'
                    )
                ))
                ->add('access_level', 'choice', array(
                    'choices' => array('1' => '1 : Amministratori', '2' => '2 : Utente', '3' => '3 : Ospite'),
                ))
                ->add('cancel', 'reset')
                ->add('save', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Codegen\UserBundle\Entity\Group'
        ));
    }

}
