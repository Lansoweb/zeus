<?php
namespace Zeus\Action\Hermes;

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

        if (!array_key_exists('source', $params) ||
            !array_key_exists('destination', $params) ||
            !array_key_exists('service', $params['source']) ||
            !array_key_exists('server', $params['source']) ||
            !array_key_exists('uri', $params['source']) ||
            !array_key_exists('service', $params['destination']) ||
            !array_key_exists('server', $params['destination']) ||
            !array_key_exists('uri', $params['destination'])
            ) {
                throw new \RuntimeException(422, 'Missing source/destination keys');
        }

        $apiKey = $params['api_key'] ?? null;
        if ($apiKey == null) {
            $header = $request->getHeader('X-Api-Key');
            if (!empty($header)) {
                $apiKey = $header[0];
            }
        }

        if (!isset($params['data'])) {
            $params['data'] = '{}';
        } elseif (is_array($params['data'])) {
            $params['data'] = json_encode($params['data']);
        }

        if (isset($params['date'])) {
            $date = $params['date'];
        } else {
            $date = microtime(true);
        }
        $date = \DateTime::createFromFormat('U.u', $date);

        $data = [
            'id' => $params['request_id'] ?? Uuid::uuid4(),
            'service' => $params['request_name'] ?? '',
            'duration' => $params['request_time'] ?? 0,
            'status' => $params['status'] ?? 1,
            'method' => $params['method'] ?? 'GET',
            'http_code' => $params['http_code'] ?? 200,
            'error' => $params['error'] ?? null,
            'depth' => $params['request_depth'] ?? 0,
            'date' => $date->format('Y-m-d H:i:s.u'),
            'data' => $params['data'] ?? null,
            'source_service' => $params['source']['service'],
            'source_server' => $params['source']['server'],
            'source_uri' => $params['source']['uri'],
            'destination_service' => $params['destination']['service'],
            'destination_server' => $params['destination']['server'],
            'destination_uri' => $params['destination']['uri'],
            'project' => $apiKey,
        ];

        $tableHermes = new TableGateway(
            'hermes',
            $this->adapter
            );
        $ret = $tableHermes->insert($data);

        if (!$ret) {
            throw new \DomainException('Insert operation failed or did not result in new row', 500);
        }

        $tableAccess = new TableGateway('access',$this->adapter);
        $ret =$tableAccess->select([
            'source' => $params['source']['service'],
            'destination' => $params['destination']['service'],
            'date' => null,
        ]);
        if (!$ret || $ret->count() == 0) {
            try {
                $tableAccess->insert([
                    'source' => $params['source']['service'],
                    'destination' => $params['destination']['service'],
                    'counter' => 0,
                ]);
            } catch (\Exception $ex) {
            }
        }
        $today = date('Y-m-d');
        $ret =$tableAccess->select([
            'source' => $params['source']['service'],
            'destination' => $params['destination']['service'],
            'date' => $today,
        ]);
        if (!$ret || $ret->count() == 0) {
            try {
                $tableAccess->insert([
                    'source' => $params['source']['service'],
                    'destination' => $params['destination']['service'],
                    'counter' => 0,
                    'date' => $today,
                ]);
            } catch (\Exception $ex) {
            }
        }

        $tableAccess->update([
            'counter' => new Expression('counter + 1'),
        ], [
            'source' => $params['source']['service'],
            'destination' => $params['destination']['service'],
            'date' => null,
        ]);

        $tableAccess->update([
            'counter' => new Expression('counter + 1'),
        ], [
            'source' => $params['source']['service'],
            'destination' => $params['destination']['service'],
            'date' => $today,
        ]);

        return new EmptyResponse(201);
    }
}
