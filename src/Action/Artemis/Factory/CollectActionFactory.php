<?php
namespace Zeus\Action\Artemis\Factory;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zeus\Action\Artemis\CollectAction;

class CollectActionFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $adapter = new Adapter($config['db']);

        return new CollectAction($adapter);
    }
}
