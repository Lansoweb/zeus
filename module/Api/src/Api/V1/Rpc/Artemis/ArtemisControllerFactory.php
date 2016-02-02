<?php
namespace Api\V1\Rpc\Artemis;

class ArtemisControllerFactory
{
    public function __invoke($controllers)
    {
        return new ArtemisController($controllers->getServiceLocator()->get('ApiDB'));
    }
}
