<?php
namespace Api\V1\Rpc\Artemis;

use Zend\Db\TableGateway\TableGateway;

class ArtemisControllerFactory
{
    public function __invoke($controllers)
    {
        return new ArtemisController($controllers->getServiceLocator()->get('ApiDB'));
    }
}
