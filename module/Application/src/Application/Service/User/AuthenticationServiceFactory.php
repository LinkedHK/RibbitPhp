<?php
/**
 * 
 * User: Windows
 * Date: 1/19/14
 * Time: 1:54 PM
 * 
 */

namespace Application\Service\User;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationServiceFactory implements   FactoryInterface {
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $authService = new AuthenticationService();
        $user_repository = $serviceLocator->get('RepositoryAccessor')->get('users');
        $sessionManager = $serviceLocator->get('Zend\Session\SessionManager');
        $config = $serviceLocator->get('Config');
        $authService->setUnderDev(isset($config['dev_mode']['under_dev']) ? $config['dev_mode']['under_dev'] : false);
        unset($config);
        $authService->setSessionManager($sessionManager);
        $authService->setUserRepository($user_repository);
        return $authService;
    }


} 