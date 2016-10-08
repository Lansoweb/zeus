<?php
namespace Zeus\Action\Hermes\Factory;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zeus\Action\Hermes\GraphAction;

class GraphActionFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $adapter = new Adapter($config['db']);

        return new GraphAction($adapter, $container->get(TemplateRendererInterface::class));
    }
}
