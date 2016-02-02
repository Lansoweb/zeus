<?php
namespace Api\V1\Rpc\Lachesis;

class LachesisControllerFactory
{
    public function __invoke($controllers)
    {
        return new LachesisController($controllers->getServiceLocator()->get('ApiDB'));
    }
}
