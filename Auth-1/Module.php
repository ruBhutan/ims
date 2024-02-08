<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Acl\Comman\UserACL;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $this->initSession([
            'remember_me_seconds' => 7000,
            'use_cookies' => true,
            'cookie_lifetime' => 7000,
            'gc_maxlifetime' => 7000,
        ]);
        
        $sm = $e->getApplication()->getServiceManager();
        $authPlugin = $sm->get('ControllerPluginManager')->get('AuthPlugin');
        $request = $sm->get('Request');
        if (!$authPlugin->isLoggedIn()) {
            $uri = $request->getServer('REDIRECT_BASE');
            if (strpos($request->getRequestUri(), '/auth/view-login') === FALSE && strpos($request->getRequestUri(), '/auth/login') === FALSE
                &&
                strpos($request->getRequestUri(), '/auth/view-register') === FALSE && strpos($request->getRequestUri(), '/auth/register') === FALSE
 		&&
                strpos($request->getRequestUri(), '/auth/forgot-password') === FALSE && strpos($request->getRequestUri(), '/auth/password') === FALSE){
		
		/*&&
                strpos($request->getRequestUri(), '/auth/authToken') === FALSE) { */
                $url = $uri . 'auth/view-login';
                $response = $e->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit();
            }
        }
        
        $manager = $e->getApplication()->getServiceManager()->get('Zend\Session\ManagerInterface');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'));
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));
        $eventManager->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'));
    }

    public function onDispatch(MvcEvent $e)
    {
        
    }

    public function onFinish(MvcEvent $e)
    {
        
    }

    public function onRoute(MvcEvent $e) {
        $sm = $e->getApplication()->getServiceManager();
        $authPlugin = $sm->get('ControllerPluginManager')->get('AuthPlugin');
        
        if (!$authPlugin->isLoggedIn()) {
            $whiteListAction = array(
                'Auth\Controller\Auth' => array('login', 'viewLogin', 'register', 'viewRegister', 'password', 'forgotPassword') //, 'authToken')
            );
            $routeMatch = $e->getRouteMatch();
            $router = $e->getRouter();
            $action = $routeMatch->getParam('action');
            $controller = $routeMatch->getParam('controller');
            if (!array_key_exists($controller, $whiteListAction) || (isset($whiteListAction[$controller]) && count($whiteListAction[$controller]) > 0 && !in_array($action, $whiteListAction[$controller]))) {
                $uri = $router->getRequestUri();
                $url = $uri->getPath();
                $queryString = $uri->getQuery();
                if (!empty($queryString))
                    $url.='?' . $uri->getQuery();
                if ($action === 'view-register') {
                  $action = 'viewRegister';
                }
		else if ($action === 'forgot-password') {
                  $action = 'forgotPassword';
                }
		/* else if ($action === 'authToken') {
                  $action = 'authToken';
                }*/ else {
                  $action = 'viewLogin';
                }
                $e->getRouteMatch()
                        ->setParam('controller', 'Auth\Controller\Auth')
                        ->setParam('action', $action);
            }else{
            }
            
        } else if ($authPlugin->isLoggedIn()) {
            $routeMatch = $e->getRouteMatch();

            $router = $e->getRouter();
            $action = $routeMatch->getParam('action');
            $route = $routeMatch->getMatchedRouteName();
            $controller = $routeMatch->getParam('controller');
            
            $acl = new UserACL($sm);
            $acl->setAllRules();
            
            if ($acl->isRouteAccessible($route)) {
                $e->getRouteMatch()
                     ->setParam('controller', $controller)
                     ->setParam('action', $action);
            } else {
                $e->getRouteMatch()
                    ->setParam('controller', 'Acl\Controller\Acl')
                    ->setParam('action', 'unauthorized');
            }
        }
    }

    /**
     *  Module Specific Config File
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Set Autoloader Config Path
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function($serviceManager) {
                    // If you are using DoctrineORMModule:
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');

                    // If you are using DoctrineODMModule:
//                    return $serviceManager->get('doctrine.authenticationservice.odm_default');
                }
            )
        );
    }
    public function initSession($config)
    {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config);
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->start();
        Container::setDefaultManager($sessionManager);
    }
}
