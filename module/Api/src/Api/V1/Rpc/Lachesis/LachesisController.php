<?php
namespace Api\V1\Rpc\Lachesis;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Adapter\Adapter;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use Zend\Db\TableGateway\TableGateway;
use ZF\ContentNegotiation\ViewModel;
use Ramsey\Uuid\Uuid;

class LachesisController extends AbstractActionController
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function lachesisAction()
    {
        $params = $this->bodyParams();

        if (!array_key_exists('type', $params) ||
            !array_key_exists('sql', $params) ||
            !array_key_exists('start', $params) ||
            !array_key_exists('end', $params)
            ) {
                return new ApiProblemResponse(new ApiProblem(422, 'Missing body/request keys'));
            }

            $start = \DateTime::createFromFormat('U.u', $params['start']);
            $end = \DateTime::createFromFormat('U.u', $params['end']);

            $project = isset($params['api_key']) ? $params['api_key'] : null;

            $trace = json_encode($params['stack'], null, 100);

            $table = new TableGateway('lachesis',$this->adapter);

            $data = [
                'id' => Uuid::uuid4(),
                'start' => $start->format('Y-m-d H:i:s.u'),
                'end' => $end->format('Y-m-d H:i:s.u'),
                'trace' => $trace,
                'type' => $params['type'],
                'elapsed' => $params['elapsed'],
                'sql' => $params['sql'],
                'project' => $project,
                'parameters' => json_encode($params['parameters'] ?? null, null, 100),
            ];

            $table = new TableGateway('lachesis',$this->adapter);
            $ret = $table->insert($data);

            if (!$ret) {
                throw new \DomainException('Insert operation failed or did not result in new row', 500);
            }

            $this->getResponse()->setStatusCode(201);
            return new ViewModel();
    }
}
