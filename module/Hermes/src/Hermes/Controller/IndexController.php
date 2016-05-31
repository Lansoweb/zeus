<?php
namespace Hermes\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Form\Form;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $form = $this->getSearchForm();

        if (!$this->getRequest()->isPost()) {
            return [
                'form' => $form,
            ];
        }

        $data = $this->getRequest()->getPost();

        $form->setData($data);

        /*
        if (!$form->isValid()) {
            return [
                'form' => $form,
            ];
        }*/
        $project = $this->params('project', 0);

        return $this->redirect()->toRoute('zeus/hermes/request-view', ['project' => $project, 'id' => $form->get('request')->getValue()]);
    }

    private function getSearchForm()
    {
        $form = new Form();
        $form->add([
            'name' => 'request',
            'options' => array(
                'label' => 'Request Id',
            ),
            'attributes' => [
                'placeholder' => 'Request Id',
            ],
            'type'  => 'Text',
        ]);
        $form->add([
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Submit',
                'class' => 'btn btn-primary',
            ),
        ]);

        return $form;
    }

    private function fetchData()
    {
        $project = $this->params('project', '0');

        $table = new TableGateway('access',$this->getServiceLocator()->get('ApiDB'));
        $ret = $table->select(function (Select $select) use ($project) {
            $select->where->isNull('date');
            /*if (empty($project)) {
                $select->where->isNull('project');
            } else {
                $select->where->equalTo('project', $project);
            }*/
            $select->order('source ASC');
        });
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
        $project = $this->params('project', '0');

        $table = new TableGateway('hermes',$this->getServiceLocator()->get('ApiDB'));
        $ret = $table->select(function (Select $select) use ($id, $project) {
            $select->where->equalTo('id', $id);
            /*if (empty($project)) {
                $select->where->isNull('project');
            } else {
                $select->where->equalTo('project', $project);
            }*/
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

        $table = new TableGateway('lachesis',$this->getServiceLocator()->get('ApiDB'));
        $lachesis = $table->select(function (Select $select) use ($id) {
            $select->where->equalTo('request_id', $id);
            $select->order('start ASC');
        });

        $table = new TableGateway('artemis_occurrences',$this->getServiceLocator()->get('ApiDB'));

        $sqlSelect = $table->getSql()->select();
        $sqlSelect->columns(['date']);
        $sqlSelect->join('artemis', 'artemis.id = artemis_occurrences.artemis_id', ['title']);
        $sqlSelect->where(['artemis_occurrences.request_id' => $id]);
        $artemis = $table->selectWith($sqlSelect);

        return [
            'nodes' => $nodes,
            'edges' => $edges,
            'max' => $maxCounter,
            'artemis' => $artemis,
            'lachesis' => $lachesis,
        ];
    }
}
