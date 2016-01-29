<?php
namespace Api\V1\Rpc\Artemis;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\TableGateway\TableGateway;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use Ramsey\Uuid\Uuid;
use ZF\ContentNegotiation\ViewModel;

class ArtemisController extends AbstractActionController
{
    private $table;

    public function __construct(TableGateway $table)
    {
        $this->table = $table;
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

        if (isset($params['time'])) {
            $date = $params['time'];
        } else {
            $date = microtime(true);
        }
        $date = \DateTime::createFromFormat('U.u', $date);

        $data = [
            'id' => Uuid::uuid4(),
            'date' => $date->format('Y-m-d H:i:s.u'),
            'file' => $params['body']['trace']['frames'][0]['filename'],
            'lineno' => $params['body']['trace']['frames'][0]['lineno'],
            'exception' => $params['body']['trace']['exception']['class'],
            'body' => json_encode($params['body'], null, 100),
            'method' => $params['request']['method'] ?? 'GET',
            'server' => json_encode($params['server'] ?? null, null, 100),
            'user' => $params['user'] ?? null,
            'trace' => json_encode($params['trace'] ?? null, null, 100),
            'request' => json_encode($params['request'] ?? null, null, 100),
        ];

        $ret = $this->table->insert($data);

        if (!$ret) {
            throw new \DomainException('Insert operation failed or did not result in new row', 500);
        }

        $this->getResponse()->setStatusCode(201);
        return new ViewModel();
    }
}

