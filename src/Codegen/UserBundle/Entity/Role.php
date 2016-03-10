<?php

namespace Codegen\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Codegen\UserBundle\Entity\Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="Codegen\UserBundle\Entity\RoleRepository")
 */
class Role {

    /**
     * @ORM\Column(type="integer") 
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=45, unique=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $accessLevel;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Role
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set accessLevel
     *
     * @param integer $accessLevel
     * @return Role
     */
    public function setAccessLevel($accessLevel) {
        $this->accessLevel = $accessLevel;

        return $this;
    }

    /**
     * Get accessLevel
     *
     * @return integer 
     */
    public function getAccessLevel() {
        return $this->accessLevel;
    }

    public function getAssocUsers($roleId) {

        $query = $this->getEntityManager()
                ->createQuery('
            SELECT u.id,u.username,u.firstname,u.lastname,u.image,u.email,u.password,u.isActive
                FROM CodegenUserBundle:User u
               WHERE u.role=' . $roleId
        );

        try {
            return $query->getResults();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

}
