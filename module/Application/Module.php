<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Domain\DbLayerConcrete\GeneralRepository;
use Composer\Console\Application;
use Zend\Db\Sql\Sql;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public  function  getServiceConfig()
    {
        return array(
            'invokables' => array(

            ),
           'factories' => array(

         'GeneralRepository' => function($sm){
                 $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                 $sql = new Sql($dbAdapter);
                 $repository  = new GeneralRepository();
                 $repository->setSqlManager($sql);
                 $repository->setDbAdapter($dbAdapter);
                 $repository->setEntityManager('doctrine.entitymanager.orm_default');
                 $repository->setServiceLocator($sm);

                 return $repository;
             }
           )
        );

    }
}
