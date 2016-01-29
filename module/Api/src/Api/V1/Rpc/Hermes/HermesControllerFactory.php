<?php
namespace Api\V1\Rpc\Hermes;

use Zend\Db\TableGateway\TableGateway;

class HermesControllerFactory
{
    public function __invoke($controllers)
    {
        $tableGateway = new TableGateway('hermes',$controllers->getServiceLocator()->get('ApiDB'));
        return new HermesController($tableGateway);
    }
}
