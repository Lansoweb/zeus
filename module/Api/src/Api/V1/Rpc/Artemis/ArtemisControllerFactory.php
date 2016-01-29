<?php
namespace Api\V1\Rpc\Artemis;

use Zend\Db\TableGateway\TableGateway;

class ArtemisControllerFactory
{
    public function __invoke($controllers)
    {
        $tableGateway = new TableGateway('artemis',$controllers->getServiceLocator()->get('ApiDB'));
        return new ArtemisController($tableGateway);
    }
}
