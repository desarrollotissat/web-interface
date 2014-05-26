<?php

namespace Cloud\UsuarioBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;



class UsuarioRegistroType extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder, array $options)	{
						
		$builder
		->add('nombre','text',array('required' => true, 'label' => 'Name'))
		->add('apellido1','text',array('label' => 'Surname 1','required' => true))
		->add('apellido2','text',array('label' => 'Surname 2'))
		->add('email', 'text',array('label' => 'Email'))
		->add('password', 'repeated', array(
		'type' => 'password',
		'invalid_message' => 'Las dos contraseñas deben coincidir',
		'options' => array('label' => 'Password'),
		'required' => true
		))
				
		->add('fecha_nacimiento', 'date', array('format' => 'dd MM yyyy','widget' => 'choice','years' => range(1890, date('Y')),'label' => 'D.O.B'))
		->add('movil','text',array('label' => 'Mobile phone','required' => false))
		//->add('perfil', 'entity', array('class' => 'CloudUsuarioBundle:Perfiles','property' => 'Nombre',))		
		//->add('grupo', 'entity', array('class' => 'CloudUsuarioBundle:Grupos','property' => 'Nombre',))		
		//->add('estado', 'entity', array('class' => 'CloudUsuarioBundle:Estados','property' => 'Nombre',))
		->add('imagen','file',array('label' => 'Change image','required' => false))
		->add('recordatorio','text',array('label' => 'Password hint','required' => false))
		->add('idioma', 'entity', array('class' => 'CloudUsuarioBundle:Idiomas','property' => 'Nombreidioma','label' => 'Language'))
            ->add('recaptcha', 'recaptcha', array(
                'widget_options' => array(
                    'theme' => 'clean'
                )
            ));




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