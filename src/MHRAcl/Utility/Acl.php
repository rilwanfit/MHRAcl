<?php
/**
 * Created by PhpStorm.
 * User: mhrilwan
 * Date: 4/24/14
 * Time: 1:01 PM
 */

namespace MHRAcl\Utility;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Acl extends ZendAcl implements ServiceLocatorAwareInterface
{

    const DEFAULT_ROLE = 'Guest';

    protected $_roleTableObject;

    protected $serviceLocator;

    /**
     * Entity manager instance
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;


    protected $roles;

    protected $permissions;

    protected $resources;

    protected $rolePermission;

    protected $commonPermission;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function initAcl()
    {
        $this->roles = $this->_getAllRoles();
        $this->resources = $this->_getAllResources();
        $this->rolePermission = $this->_getRolePermissions();

        // we are not putting these resource & permission in table bcz it is
        // common to all user
        $this->commonPermission = array(
            'MHRUser\Controller\Index' => array(
                'logout',
                'index'
            )
        );
        $this->_addRoles()
            ->_addResources()
            ->_addRoleResources();
    }

    public function isAccessAllowed($role, $resource, $permission)
    {
        if (! $this->hasResource($resource)) {
            return false;
        }
        if ($this->isAllowed($role, $resource, $permission)) {
            return true;
        }
        return false;
    }

    protected function _addRoles()
    {
        $this->addRole(new Role(self::DEFAULT_ROLE));
        if (! empty($this->roles)) {
            foreach ($this->roles as $role) {
                $roleName = $role->getRoleName();
                if (! $this->hasRole($roleName)) {
                    $this->addRole(new Role($roleName), self::DEFAULT_ROLE);
                }
            }
        }
        return $this;
    }

    protected function _addResources()
    {
        if (! empty($this->resources)) {
            foreach ($this->resources as $resource) {
                if (! $this->hasResource($resource->getResourceName())) {
                    $this->addResource(new Resource($resource->getResourceName()));
                }
            }
        }
        // add common resources
        if (! empty($this->commonPermission)) {
            foreach ($this->commonPermission as $resource => $permissions) {
                if (! $this->hasResource($resource)) {
                    $this->addResource(new Resource($resource));
                }
            }
        }

        return $this;
    }

    protected function _addRoleResources()
    {
        // allow common resource/permission to guest user
        if (! empty($this->commonPermission)) {
            foreach ($this->commonPermission as $resource => $permissions) {
                foreach ($permissions as $permission) {
                    $this->allow(self::DEFAULT_ROLE, $resource, $permission);
                }
            }
        }
        if (! empty($this->rolePermission)) {
            foreach ($this->rolePermission as $rolePermissions) {
                $this->allow($rolePermissions['roleName'], $rolePermissions['resourceName'], $rolePermissions['permissionName']);
            }
        }
        return $this;
    }

    protected function _getAllRoles()
    {

        // ZendDb
//        $roleTable = $this->getServiceLocator()->get("RoleTable");
//        return $roleTable->getUserRoles();

        // Doctrine 2
        return $this->getEntityManager()->getRepository('MHRAcl\Entity\Role')->findAll();
    }

    protected function _getAllResources()
    {
        // ZendDB
//        $resourceTable = $this->getServiceLocator()->get("ResourceTable");
//        return $resourceTable->getAllResources();

        // Doctrine 2
        return $this->getEntityManager()->getRepository('MHRAcl\Entity\Resource')->findAll();
    }

    protected function _getRolePermissions()
    {
        // ZendDB
//        $rolePermissionTable = $this->getServiceLocator()->get("RolePermissionTable");
//        return $rolePermissionTable->getRolePermissions();

        // Doctrine 2
        return $this->getEntityManager()->getRepository('MHRAcl\Entity\RolePermission')->getRolePermissions();
    }

    private function debugAcl($role, $resource, $permission)
    {
        echo 'Role:-' . $role . '==>' . $resource . '\\' . $permission . '<br/>';
    }

    /**
     * Returns an instance of the Doctrine entity manager loaded from the service locator
     *
     *  @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()
                ->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }
}