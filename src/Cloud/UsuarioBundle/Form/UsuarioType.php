<?php

namespace Cloud\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface; 
use Doctrine\ORM\EntityManager;

class UsuarioType extends AbstractType{
	
	protected $em;
	
	 public function __construct(EntityManager $em = null)
    {    	
        $this->em = $em;
    }
	
	public function buildForm(FormBuilderInterface $builder, array $options)	{
			
		$idiomas = $this->em->getRepository('CloudUsuarioBundle:Idiomas')->findAll();	

		foreach ($idiomas as $key => $idioma) {
			$arrayIdiomas[$idioma->getIdidioma()] = $idioma->getNombreidioma();			
		}
		
				
		$builder
		->add('nombre','text',array('required' => true, 'label' => 'Name'))
		->add('apellido1','text',array('label' => 'Surname 1','required' => true))
		->add('apellido2','text',array('label' => 'Surname 2'))
		->add('email', 'text',array('label' => 'Username (email)', 'disabled' => true))
		->add('password', 'repeated', array(
		'type' => 'password',
		'invalid_message' => 'Both password must match',
		'options' => array('label' => 'Password (repeat)'),
		'empty_data' => false,
		'required' => false
		))
		//->add('password', 'password',array('label' => 'Contraseña','required' => false))		
		
		//->add('contrasenya', 'password',array('label' => 'Contraseña (repetir)','required' => false))
		
		->add('fecha_nacimiento', 'date', array('format' => 'dd MM yyyy','widget' => 'choice','years' => range(1890, date('Y')),'label' => 'DOB'))
		->add('movil','text',array('label' => 'Mobile phone'))
		->add('perfil', 'entity', array('class' => 'CloudUsuarioBundle:Perfiles','property' => 'Nombre','label' => 'Profile'))
		->add('grupo', 'entity', array('class' => 'CloudUsuarioBundle:Grupos','property' => 'Nombre','label' => 'Group'))
		->add('estado', 'entity', array('class' => 'CloudUsuarioBundle:Estados','property' => 'Nombre','label' => 'Status'))
		->add('imagen','file',array('label' => 'Change image','required' => false))
		->add('recordatorio','text',array('label' => 'Password hint','required' => false))
		->add('Ultimoacceso','text',array('label' => 'Last access','read_only' => true))
		->add('idioma', 'entity', array('class' => 'CloudUsuarioBundle:Idiomas','property' => 'Nombreidioma','label' => 'Languages'));
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