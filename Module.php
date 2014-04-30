<?php
/**
 * Created by PhpStorm.
 * User: mhrilwan
 * Date: 4/24/14
 * Time: 11:09 AM
 */

namespace MHRAcl;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

use MHRAcl\Utility\Acl;

class Module
{

    private $app;

    private $serviceManager;

    public function onBootstrap(MvcEvent $e)
    {

        $this->app = $e->getApplication();
        $this->serviceManager = $this->app->getServiceManager();

        $em = $e->getApplication()->getEventManager();
        $oModuleRouteListerner = new ModuleRouteListener();
        $oModuleRouteListerner->attach($em);

        $em->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'beforeDispatch',
        ), 100);
    }

    public function beforeDispatch(MvcEvent $e)
    {
        $response = $e->getResponse();

        $whiteList = array(
            'MHRUser\Controller\Index-index',
            'MHRUser\Controller\Index-logout'
        );

        $controller = $e->getRouteMatch()->getParam('controller');
        $action = $e->getRouteMatch()->getParam('action');

        $requestedResourse = $controller . "-" . $action;

        $session = new Container('User');

       // if ($session->offsetExists('email')) {
            if ($requestedResourse == 'MHRUser\Controller\Index-index' || in_array($requestedResourse, $whiteList)) {
                $url = '/';
                $response->setHeaders($response->getHeaders()
                    ->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
            } else {
                $serviceManager = $e->getApplication()->getServiceManager();


                // check user logged into system
                $auth = $this->serviceManager->get('doctrine.authenticationservice.orm_default');
                if($user = $auth->getIdentity()) {
                        echo 'Logged in as ' . $user->getFirstName();
                    // Store it in session
                    $session = new Container('User');
                    $session->offsetSet('email', $user->getEmail());
                    $session->offsetSet('userId', $user->getId());


                    // TODO :: get role details
                    $userRole = 'Administrator';

                    //$session->offsetSet('roleId', $user->getId());
                    //$session->offsetSet('roleName', $user->getId());
                    //$userRole = $session->offsetGet('roleName');

                } else {
                    echo 'Not logged in';
                    $userRole = Acl::DEFAULT_ROLE;
                }

                $acl = $serviceManager->get('Acl');
                $acl->initAcl();

                var_dump("Role :: ".$userRole . " Controller :: " . $controller . " Action :: " . $action);

                $status = $acl->isAccessAllowed($userRole, $controller, $action);
                if (! $status) {

                    $response = $e->getResponse();
                    //location to page or what ever
                    $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . '/404');
                    $response->setStatusCode(404);

                    die('Permission denied');
                }
            }
//        } else {
//
//            if ($requestedResourse != 'MHRUser\Controller\Index-index' && ! in_array($requestedResourse, $whiteList)) {
//                $url = '/login';
//                $response->setHeaders($response->getHeaders()
//                    ->addHeaderLine('Location', $url));
//                $response->setStatusCode(302);
//            }
//            $response->sendHeaders();
//        }
    }
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Acl' => function ($serviceManager)
                    {
                        return new Acl();
                    },
                'mhracl_role_form' => function ($sm) {
                        $oForm = new Form\Role(null);
                        return $oForm;
                    },
                'mhracl_managerole_form' => function ($sm) {
                        $oForm = new Form\ManageRole(null);
                        return $oForm;
                    },
            ),
        );
    }
} 