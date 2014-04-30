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
 * Class RolePremissionRepository
 * @package MHRUser\Entity\Repository
 */
class RolePermissionRepository extends EntityRepository
{
    public function getRolesArray($number = 30)
    {
        // $dql = "SELECT b, e, r, p FROM \GraceDrops\Entity\User b JOIN b.engineer e ".
        // "JOIN b.reporter r JOIN b.products p ORDER BY b.created DESC";
        // $query = $this->getEntityManager()->createQuery($dql);
        // $query->setMaxResults($number);
        // return $query->getArrayResult();
        return array();
    }
    public function getRolePermissions()
    {
        // ZendDb
//        $sql = new Sql($this->getAdapter());
//        $select = $sql->select()
//            ->from(array(
//                't1' => 'role'
//            ))
//            ->columns(array(
//                'role_name'
//            ))
//            ->join(array(
//                't2' => $this->table
//            ), 't1.rid = t2.role_id', array(), 'left')
//            ->join(array(
//                't3' => 'permission'
//            ), 't3.id = t2.permission_id', array(
//                'permission_name'
//            ), 'left')
//            ->join(array(
//                't4' => 'resource'
//            ), 't4.id = t3.resource_id', array(
//                'resource_name'
//            ), 'left')
//            ->where('t3.permission_name is not null and t4.resource_name is not null')
//            ->order('t1.rid');
//
//        $statement = $sql->prepareStatementForSqlObject($select);
//        $result = $this->resultSetPrototype->initialize($statement->execute())
//            ->toArray();
//        return $result;

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