<?php
/**
 * 
 * User: Windows
 * Date: 1/9/14
 * Time: 1:31 PM
 * 
 */

namespace Application\Domain\DbLayerInterfaces;


interface RepositoryInterface {

    public function execute($statement);

    public function getSqlManager();

    public function getDbAdapter();

    public  function getServiceLocator();

} 