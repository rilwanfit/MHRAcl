<?php
/**
 * Created by PhpStorm.
 * User: mhrilwan
 * Date: 3/20/14
 * Time: 12:15 PM
 */

namespace MHRAcl\Entity\Repository;

use Doctrine\ORM\EntityRepository;

use Zend\Db\Sql\Sql;

/**
 * Repository Table Data Gateway
 *
 * Class UserRoleRepository
 * @package MHRUser\Entity\Repository
 */
class UserRoleRepository extends EntityRepository
{
    public function getUserRolesArray($number = 30)
    {
        $qb  = $this->getEntityManager()->createQueryBuilder();

        $qb->select(array('r.roleName', 'p.permissionName', 'rs.resourceName'))
            ->from('MHRAcl\Entity\Role', 'r')
            ->leftJoin('r.permissions', 'p')
            ->leftJoin('p.resources', 'rs')
            ->where('p.permissionName is not null and rs.resourceName is not null')
            ->orderBy('r.rid');

        //echo '<pre>';print_r($qb->getQuery());
        return $qb->getQuery()->getArrayResult();
    }
} 