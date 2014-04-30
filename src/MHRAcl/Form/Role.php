<?php
/**
 * Created by PhpStorm.
 * User: mhrilwan
 * Date: 3/18/14
 * Time: 2:47 PM
 */

namespace MHRAcl\Form;

use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Permissions\Acl\Role\Registry;


class Role extends Form
{

    /**
     * @param string|null $name
     */
    public function __construct($name = null)
    {

        parent::__construct($name);

        //$em = Registry::get('entityManager');

        //$this->setHydrator(new DoctrineEntity($em))
          //  ->setObject(new Role());

        $this->add(array(
            'name' => 'roleName',
            'options' => array(
                'label' => 'Role Name'
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => 'required'
            )
        ));

        $submitElement = new Element\Button('submit');
        $submitElement
            ->setLabel('Add')
            ->setAttributes(array(
                'type'  => 'submit',
            ));

        $this->add($submitElement, array(
            'priority' => -100,
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        $this->get('submit')->setLabel('Register');
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}