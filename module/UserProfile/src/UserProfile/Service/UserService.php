<?php
/**
 * 
 * User: Windows
 * Date: 2/7/14
 * Time: 8:16 PM
 * 
 */

namespace UserProfile\Service;


use UserProfile\Entity\UserProfile;
use UserProfile\Service\Interfaces\UserProfileServiceInterface;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class UserProfileService implements ServiceLocatorAwareInterface, UserProfileServiceInterface{


    protected $serviceLocator;

    protected $profileCacheService;

    protected $userProfile;

    protected $userRepository;

    function getUserProfile($username, $fromCache = true)
    {
        $result = null;
        if($fromCache){
         $result = $this->getProfileCacheService()->getUserProfile($username);
        }
       if(!$result){
           $id = $this->getUserRepository()->findByUsername($username,array('id'));
           if($id){
               $user_table = $this->getUserRepository()->getTable();
               $profile_table = $this->getUserProfileRepository()->getTable();
               $query = "select
                 $user_table.id,
                 $user_table.username,
                 $user_table.email,
                 $profile_table.first_name,
                 $profile_table.last_name,
                 $profile_table.user_id
                 from $user_table
                 join $profile_table on ($user_table.id = $profile_table.user_id )
                 where $user_table.username = '$username'
                 Limit 1";

                  $result = $this->getUserRepository()->execute($query)->toArray();
                  $dd = new UserProfile($result[0]);
                  $result = get_object_vars($dd);
                  $this->getProfileCacheService()->setUserProfile($result['username'], $result);
           }
       }
        return  $result;
    }

    public function isProfileOwner($user_id){

     $owner = false;

     $auth_service =  $this->getServiceLocator()->get('AuthService');

        if($user_id && $auth_service->is_identified()){
            $id =  $auth_service->getUserIdentify("id");
            if($id == $user_id) $owner = true;
        }
    return $owner;
    }
    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {

        $this->serviceLocator = $serviceLocator;

    }
    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @return mixed
     */
    public function getUserProfileRepository()
    {
        return $this->getServiceLocator()->get('user_profile_repository');
    }
    /**
     * @return mixed
     */
    public function getUserRepository()
    {
        return  $this->getServiceLocator()->get('user_repository');
    }


    /**
     * @param mixed $globalCacheService
     */
    public function setProfileCacheService($profileCacheService)
    {
        $this->profileCacheService = $profileCacheService;
    }

    /**
     * @return mixed
     */
    public function getProfileCacheService()
    {
        return $this->profileCacheService;
    }

} 