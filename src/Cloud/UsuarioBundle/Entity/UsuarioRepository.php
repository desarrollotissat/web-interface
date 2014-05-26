<?php

namespace Cloud\UsuarioBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UsuarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UsuarioRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM CloudUsuarioBundle:Usuarios p ORDER BY p.email ASC'
            )
            ->getResult();
    }

    public function findAllUsuariosDQL(){
        $query = $this->getEntityManager()->createQuery('SELECT u FROM CloudUsuarioBundle:Usuarios u ORDER BY u.email ASC');
        return $query;
    }

    public function deleteUserbyName($name){

        $usuario=$this->getEntityManager()
            ->getRepository('CloudloginBundle:PasswordReset')
            ->findBy(array(
                'email' => $name
            ));

    }
}