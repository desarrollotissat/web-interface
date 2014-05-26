<?php

namespace Cloud\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;



class UsuarioRegistroNoCaptchaType extends AbstractType{

	public function buildForm(FormBuilderInterface $builder, array $options)	{
						
		$builder
        ->add('email', 'text',array('label' => 'Email'))
        ->add('quota_limit','number',array('required' => true, 'label' => 'Quota Limit', 'mapped' => false ))
        ->add('nombre','text',array('required' => true, 'label' => 'Name'))
		->add('apellido1','text',array('label' => 'Surname 1','required' => false))
		->add('apellido2','text',array('label' => 'Surname 2', 'required' => false))

		->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Both passwords must match',
                'options' => array('label' => 'Password'),
                'empty_data' => false,
                'required' => false
                )
            )
		->add('fecha_nacimiento', 'date', array('format' => 'dd MM yyyy','widget' => 'choice','years' => range(1890, date('Y')),'label' => 'D.O.B'));

	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cloud\UsuarioBundle\Entity\Usuarios',
            'csrf_protection' => false            
        ));
    } 
		 
	
	public function getName()
    {
        return 'user_new';
    }
	
}