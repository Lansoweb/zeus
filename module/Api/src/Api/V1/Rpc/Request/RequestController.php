<?php
namespace Api\V1\Rpc\Request;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\TableGateway\TableGateway;
use Ramsey\Uuid\Uuid;
use ZF\ContentNegotiation\ViewModel;

class RequestController extends AbstractActionController
{
    private $table;

    public function __construct(TableGateway $table)
    {
        $this->table = $table;
    }

    public function requestAction()
    {
        $params = $this->bodyParams();

        if (! isset($params['id'])) {
            $params['id'] = Uuid::uuid4();
        }

        $ret = $this->table->insert($params);

        if (!$ret) {
            throw new \DomainException('Insert operation failed or did not result in new row', 500);
        }

        $this->getResponse()->setStatusCode(201);
        return new ViewModel();
    }
}
