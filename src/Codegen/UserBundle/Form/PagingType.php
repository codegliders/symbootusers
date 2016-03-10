<?php
 
namespace Codegen\UserBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
 
class PagingType extends AbstractType
{
 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('numRecords', 'choice', array(
                    'choices' => array('5' => '5', '10' => '10', '20' => '20'),
                    'required' => false,
                    'empty_value' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'style' => 'width:80px',
                    )
                        )
                );
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            // ... adding the name field if needed
        });
    }
 
    public function getName()
    {
        return 'numRecords';
    }
 
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class' => 'Acme\DemoBundle\Entity\Article',
        ));
    }
 
}

