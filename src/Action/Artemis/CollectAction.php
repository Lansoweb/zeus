<?php
namespace Zeus\Action\Artemis;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGateway;
use Zend\Diactoros\Response\EmptyResponse;
use Zeus\Uuid;
use Zend\Db\Adapter\Adapter;

class CollectAction
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $next
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $params = $request->getParsedBody();

        if (!array_key_exists('body', $params) ||
            !array_key_exists('request', $params) ||
            !array_key_exists('trace', $params['body'])
            ) {
                throw new \RuntimeException(422, 'Missing body/request keys');
        }

        $date = isset($params['time']) ? $params['time'] : microtime(true);
        $date = \DateTime::createFromFormat('U.u', $date);

        $project = $params['api_key'] ?? null;

        if (isset($params['body']['trace']['exception']['message'])) {
            $title = $params['body']['trace']['exception']['class'] .': '. $params['body']['trace']['exception']['message'];
        } else {
            $title = $params['body']['trace']['exception']['class'];
        }
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

        $requestId = null;
        if (isset($params['request']['headers']['x-request-id'])) {
            $requestId = $params['request']['headers']['x-request-id'];
        } elseif (isset($params['request']['headers']['X-Request-Id'])) {
            $requestId = $params['request']['headers']['X-Request-Id'];
        }

        $data = [
            'id' => Uuid::uuid4(),
            'artemis_id' => $id,
            'date' => $date->format('Y-m-d H:i:s.u'),
            'exception' => json_encode($params['body']['trace']['exception'], null, 100),
            'method' => $params['request']['method'] ?? 'GET',
            'server' => json_encode($params['server'] ?? null, null, 100),
            'user' => $params['user'] ?? null,
            'request' => json_encode($params['request'] ?? null, null, 100),
            'request_id' => $requestId,
        ];

        $table = new TableGateway('artemis_occurrences',$this->adapter);
        $ret = $table->insert($data);

        if (!$ret) {
            throw new \DomainException('Insert operation failed or did not result in new row', 500);
        }

        return new EmptyResponse(201);
    }
}
