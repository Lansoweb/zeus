<?php
namespace Lachesis\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $project = $this->params('project', '0');

        $table = new TableGateway('lachesis',$this->getServiceLocator()->get('ApiDB'));
        $ret = $table->select(function (Select $select) use ($project) {
            if (empty($project)) {
                $select->where->isNull('project');
            } else {
                $select->where(['project' => $project]);
            }
            $select->order('start DESC');
            $select->limit(20);
        });
        return [
            'list' => $ret,
            'project' => $project,
        ];
    }

    public function detailAction()
    {
        $project = $this->params('project', '0');
        $id = $this->params('id');

        $table = new TableGateway('lachesis',$this->getServiceLocator()->get('ApiDB'));
        $detail = $table->select(function (Select $select) use ($id) {
            $select->where->equalTo('id', $id);
        });

        return [
            'detail' => $detail->current(),
            'project' => $project,
        ];
    }

    public function occurrenceAction()
    {
        $project = $this->params('project', '0');
        $id = $this->params('id');

        $table = new TableGateway('artemis_occurrences',$this->getServiceLocator()->get('ApiDB'));
        $occurrence = $table->select(function (Select $select) use ($id) {
            $select->where->equalTo('id', $id);
        })->current();

        $artemisId = $occurrence->artemis_id;

        $table = new TableGateway('artemis',$this->getServiceLocator()->get('ApiDB'));
        $detail = $table->select(function (Select $select) use ($artemisId) {
            $select->where->equalTo('id', $artemisId);
        })->current();

        return [
            'detail' => $detail,
            'occurrence' => $occurrence,
            'project' => $project,
        ];
    }
}
