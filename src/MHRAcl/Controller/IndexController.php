<?php
/**
 * Created by PhpStorm.
 * User: mhrilwan
 * Date: 3/5/14
 * Time: 4:57 PM
 */

namespace MHRAcl\Controller;


use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use MHRAcl\Entity\Role;

class IndexController extends AbstractActionController
{
    /**
     * Entity manager instance
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;


    /**
     * @var Form
     */
    protected $manageRoleForm;

    /**
     * Index action displays a list of all the users
     *
     * @return array|ViewModel
     */
    public function indexAction()
    {


//        $manager        = $this->getServiceLocator()->get('ModuleManager');
//        $modules        = $manager->getLoadedModules();
//        $loadedModules      = array_keys($modules);
//        $skipActionsList    = array('notFoundAction', 'getMethodFromAction');
//
//        foreach ($loadedModules as $loadedModule) {
//            $moduleClass = '\\' .$loadedModule . '\Module';
//            $moduleObject = new $moduleClass;
//            $config = $moduleObject->getConfig();
//
//            $controllers = (isset($config['controllers']['invokables'])) ? $config['controllers']['invokables'] : array();
//            foreach ($controllers as $key => $moduleClass) {
//                $tmpArray = get_class_methods($moduleClass);
//                $controllerActions = array();
//                foreach ($tmpArray as $action) {
//                    if (substr($action, strlen($action)-6) === 'Action' && !in_array($action, $skipActionsList)) {
//                        $controllerActions[] = $action;
//                    }
//                }
//
//                var_dump($loadedModule);
//                var_dump($moduleClass);
//                print_r($controllerActions);
//            }
//        }


        $oForm = $this->getManageRoleForm();

        $aRoles = $this->getEntityManager()->getRepository('MHRAcl\Entity\Role')->findAll();

        $aResource = $this->getEntityManager()->getRepository('MHRAcl\Entity\Resource')->findAll();

        return new ViewModel(
            array(
                'roles' => $aRoles,
                'form' => $oForm
            )
        );
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


    public function getManageRoleForm()
    {
        if (!$this->manageRoleForm) {
            $this->setRoleForm($this->getServiceLocator()->get('mhracl_managerole_form'));
        }
        return $this->manageRoleForm;
    }

    public function setRoleForm(Form $manageRoleForm)
    {
        $this->manageRoleForm = $manageRoleForm;
    }
}