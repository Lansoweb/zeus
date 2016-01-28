<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        if (class_exists('\ZF\Apigility\Admin\Module', false)) {
          return $this->redirect()->toRoute('zf-apigility/ui');
        }
        return new ViewModel();
    }

    private function fetchData()
    {
        $table = new TableGateway('access',$this->getServiceLocator()->get('ApiDB'));
        $ret = $table->select([
            'date' => null,
        ]);
        $nodes = [];
        $edges = [];
        $maxCounter = 0;
        foreach ($ret as $node) {
            if (!isset($nodes[$node->source])) {
                $nodes[$node->source] = [
                    'id' => $node->source,
                    'name' => $node->source,
                    'count' => 0,
                ];
            }

            if (!isset($nodes[$node->destination])) {
                $nodes[$node->destination] = [
                    'id' => $node->destination,
                    'name' => $node->destination,
                    'count' => 0,
                ];
            }
            $nodes[$node->destination]['count'] += $node->counter;

            if (!isset($edges[$node->source.'-'.$node->destination])) {
                $edges[$node->source.'-'.$node->destination] = [
                    'source' => $node->source,
                    'target' => $node->destination,
                    'count' => 0,
                ];
            }
            $edges[$node->source.'-'.$node->destination]['count'] += $node->counter;

            if ($node->counter > $maxCounter) {
                $maxCounter = $node->counter;
            }
        }

        return [
            'nodes' => $nodes,
            'edges' => $edges,
            'max' => $maxCounter,
        ];
    }

    public function coseAction()
    {
        return ['data' => $this->fetchData()];
    }

    public function circleAction()
    {
        return ['data' => $this->fetchData()];
    }

    public function requestAction()
    {
        $id = $this->params('id');

        $table = new TableGateway('request',$this->getServiceLocator()->get('ApiDB'));
        $ret = $table->select(function (Select $select) use ($id) {
            $select->where->equalTo('id', $id);
            $select->order('depth ASC');
            $select->order('date ASC');
        });

        $nodes = [];
        $edges = [];
        $maxCounter = 0;
        foreach ($ret as $node) {
            if (!isset($nodes[$node->source_service])) {
                $nodes[$node->source_service] = [
                    'id' => $node->source_service,
                    'name' => $node->source_service,
                    'count' => 0,
                ];
            }

            if (!isset($nodes[$node->destination_service])) {
                $nodes[$node->destination_service] = [
                    'id' => $node->destination_service,
                    'name' => $node->destination_service,
                    'count' => 0,
                ];
            }

            $edges[] = [
                'source' => $node->source_service,
                'target' => $node->destination_service,
                'duration' => $node->duration,
            ];
        }

        return [
            'nodes' => $nodes,
            'edges' => $edges,
            'max' => $maxCounter,
        ];
    }
}
