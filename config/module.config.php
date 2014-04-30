<?php
/**
 * Created by PhpStorm.
 * User: mhrilwan
 * Date: 3/5/14
 * Time: 4:44 PM
 */
return array(
    'router' => array(
        'routes' => array(
            'mhr-acl' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/mhr-acl',
                    'defaults' => array(
                        '__NAMESPACE__' => 'MHRAcl\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '[-:controller][/:action][/:id]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'MHRAcl\Controller\Index'      => 'MHRAcl\Controller\IndexController',
            'MHRAcl\Controller\Role'      => 'MHRAcl\Controller\RoleController',
        ),
    ),
    'view_manager' => array(
//        'template_map' => array(
//            'mhr-acl/index/index' => __DIR__ . '/../view/mhr-acl/index/index.phtml'
//        ),
        'template_path_stack' => array(
            'mhr-acl' => __DIR__ . '/../view',
        ),
    ),
    // Doctrine Config
    'doctrine'  => array(
        'driver' => array(
            'mhracl_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths'  => array(
                    __DIR__ . '/../src/MHRAcl/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'MHRAcl\Entity' => 'mhracl_driver'
                )
            ),
        ),
    ),
);