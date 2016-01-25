<?php
namespace Api\V1\Rpc\Request;

use Zend\Db\TableGateway\TableGateway;
class RequestControllerFactory
{
    public function __invoke($controllers)
    {
        $tableGateway = new TableGateway('request',$controllers->getServiceLocator()->get('ApiDB'));
        return new RequestController($tableGateway);
    }
}
