<?php
namespace Api\V1\Rpc\Artemis;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\TableGateway\TableGateway;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use Ramsey\Uuid\Uuid;
use ZF\ContentNegotiation\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;

class ArtemisController extends AbstractActionController
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function artemisAction()
    {
        $params = $this->bodyParams();

        if (!array_key_exists('body', $params) ||
            !array_key_exists('request', $params) ||
            !array_key_exists('trace', $params['body'])
        ) {
            return new ApiProblemResponse(new ApiProblem(422, 'Missing body/request keys'));
        }

        $date = isset($params['time']) ? $params['time'] : microtime(true);
        $date = \DateTime::createFromFormat('U.u', $date);

        $project = isset($params['api_key']) ? $params['api_key'] : null;

        $title = $params['body']['trace']['exception']['class'] .': '. $params['body']['trace']['exception']['message'];
        $trace = json_encode($params['body']['trace'], null, 100);

        $table = new TableGateway('artemis',$this->adapter);
        $ret = $table->select([
            'title' => $title,
            'project' => $project,
        ]);

        if ($ret->count() == 0) {
            $table->insert([
                'title' => $title,
                'project' => $project,
                'counter' => 0,
            ]);

            $id = $table->getLastInsertValue();
        } else {
            $id = $ret->current()['id'];
        }

        $table->update([
            'counter' => new Expression('counter + 1'),
            'trace' => $trace,
            'last_seen' => $date->format('Y-m-d H:i:s.u'),
        ], ['id' => $id]);

        $data = [
            'id' => Uuid::uuid4(),
            'artemis_id' => $id,
            'date' => $date->format('Y-m-d H:i:s.u'),
            'exception' => json_encode($params['body']['trace']['exception'], null, 100),
            'method' => $params['request']['method'] ?? 'GET',
            'server' => json_encode($params['server'] ?? null, null, 100),
            'user' => $params['user'] ?? null,
            'request' => json_encode($params['request'] ?? null, null, 100),
        ];

        $table = new TableGateway('artemis_occurrences',$this->adapter);
        $ret = $table->insert($data);

        if (!$ret) {
            throw new \DomainException('Insert operation failed or did not result in new row', 500);
        }

        $this->getResponse()->setStatusCode(201);
        return new ViewModel();
    }
}

