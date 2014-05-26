<?php

namespace Cloud\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface; 


class EditUserFormType extends AbstractType{
	

	public function buildForm(FormBuilderInterface $builder, array $options){
				
		$builder
		->add('nombre','text',array('required' => true, 'label' => 'Name'))
		->add('apellido1','text',array('label' => 'Surname 1','required' => true))
		->add('apellido2','text',array('label' => 'Surname 2'))
        ->add('quota_limit','number',array('required' => true, 'label' => 'Quota Limit', 'mapped' => false ))
		->add('email', 'text',array('label' => 'Username (email)', 'disabled' => true))
		->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'Both password must match',
            'options' => array('label' => 'Password (repeat)'),
            'required' => false,
             'empty_data' => false,
		))

		->add('fecha_nacimiento', 'date', array('format' => 'dd MM yyyy','widget' => 'choice','years' => range(1890, date('Y')),'label' => 'DOB'))
		->add('movil','text',array('label' => 'Mobile phone'))
         ;

	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cloud\UsuarioBundle\Entity\Usuarios',
            'idioma' => 'integer'
        ));
    } 
		 
	
	public function getName()
    {
        return 'user_edit';
    }
	
}