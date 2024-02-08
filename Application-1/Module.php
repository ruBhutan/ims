<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;

use Application\View\Helper\NumbertoWords;
use Application\View\Helper\Decimalhelper;
use Application\View\Helper\Currencyhelper;
use Application\View\Helper\CurrencyCodehelper;
use Application\View\Helper\Statushelper;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {
		
    }

    public function onDispatch(MvcEvent $e)
    {
        
    }

    public function onFinish(MvcEvent $e)
    {
        
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

    public function getViewHelperConfig()
    {
        return array(
                'factories' => array(
                        'numtoWords' => function($sl) {
                            $sm = $sl->getServiceLocator();
                            $currencyTable = $sm->get('Accounts\CurrencyTable');
                            // Now inject it into the view helper constructor
                            return new NumbertoWords($currencyTable);
                        },
                        'currency' => function($sl) {
                            $sm = $sl->getServiceLocator();
                            $currencyTable = $sm->get('Accounts\CurrencyTable');
                            // Now inject it into the view helper constructor
                            return new Currencyhelper($currencyTable);
                        },
                        'currency_code' => function($sl) {
                            $sm = $sl->getServiceLocator();
                            $currencyTable = $sm->get('Accounts\CurrencyTable');
                            return new CurrencyCodehelper($currencyTable);
                        },
                        'status' => function($sl) {
                            $sm = $sl->getServiceLocator();
                            $statusTable = $sm->get('Accounts\StatusTable');
                            // Now inject it into the view helper constructor
                            return new Statushelper($statusTable);
                        },
                        'config' => function($serviceManager) {
                            $helper = new Confighelper($serviceManager);
                            return $helper;
                        },
                        'dateformat' => function($sl) {
                            return new DateFormathelper();
                        },
                        'decimal' => function($sl) {
                            return new Decimalhelper();
                        },
                )
        );
    }    
}