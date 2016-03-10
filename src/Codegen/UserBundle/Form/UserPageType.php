<?php

namespace Codegen\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserPageType extends AbstractType {

    public function getName() {
        return 'pagingform';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
         $builder->add('numRecords', 'choice', array(
    'choices'   => array('5' => '5', '10' => '10', '20' => '20'),
    'required'  => false,
));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Codegen\UserBundle\Entity\User'
        ));
    }

}
