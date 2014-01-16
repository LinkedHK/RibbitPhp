<?php
/**
 * 
 * User: Windows
 * Date: 1/15/14
 * Time: 8:35 PM
 * 
 */

namespace Application\Controller\Plugin;


use Application\Service\Interfaces\User\AuthenticationServiceInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\Mvc\MvcEvent;

class UserPlugin extends AbstractPlugin  {


    protected $redirect;

    protected $event;

    protected $authService;

    protected $authPath = array('route' => "index/index_child", 'action' => 'login');

    public function redirectToAuth(array $authPath = null)
    {
        if($authPath == null)
            $authPath = $this->authPath;
        $action = $this->getEvent()->getRouteMatch()->getParam('action');
        $routeName = $this->getEvent()->getRouteMatch()->getMatchedRouteName();

        return $this->getRedirect()->toRoute($authPath['route'],
            array('action' => $authPath['action']), array('query' =>
                array( 'rt' => $routeName.'/'.$action  )));
    }


    public  function requireAuth()
    {

        if(!$this->getAuthService()->is_identified()){
            return $this->redirectToAuth();
        }
        return false;
    }

    public function setRedirect(Redirect $redirect)
    {
        if(null == $this->redirect )
            $this->redirect = $redirect;

       $this->redirect = $redirect;
    }


    protected function getRedirect()
    {

        return $this->redirect;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }



    /**
     * @param mixed $authService
     */
    public function setAuthService(AuthenticationServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return mixed
     */
    public function getAuthService()
    {
        return $this->authService;
    }





} 