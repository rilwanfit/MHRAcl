<?php

namespace MHRAcl\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permission
 *
 * @ORM\Table(name="permission")
 * @ORM\Entity
 */
class Permission
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="permission_name", type="string", length=45, nullable=false)
     */
    private $permissionName;

    /**
     * @var integer
     *
     * @ORM\Column(name="resource_id", type="integer", nullable=false)
     */
    private $resourceId;


    /**
     * @ORM\ManyToOne(targetEntity="MHRAcl\Entity\Resource", inversedBy="permission")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     **/
    private $resources;


}
