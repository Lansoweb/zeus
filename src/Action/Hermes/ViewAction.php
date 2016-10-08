<?php
namespace Zeus\Action\Hermes;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGateway;
use Zend\Diactoros\Response\EmptyResponse;
use Zeus\Uuid;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Diactoros\Response\HtmlResponse;

class ViewAction
{
    private $adapter;
    private $template;

    public function __construct(Adapter $adapter, TemplateRendererInterface $template)
    {
        $this->adapter = $adapter;
        $this->template = $template;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $next
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $id = $request->getAttribute('id');
        $project = $request->getAttribute('project');

        $table = new TableGateway('hermes',$this->adapter);
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

        $requests = [];
        $nodes = [];
        $edges = [];
        $maxCounter = 0;
        foreach ($ret as $node) {
            $requests[] = $node->getArrayCopy();
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

        $table = new TableGateway('lachesis', $this->adapter);
        $lachesis = $table->select(function (Select $select) use ($id) {
            $select->where->equalTo('request_id', $id);
            $select->order('start ASC');
        });

        $table = new TableGateway('artemis_occurrences',$this->adapter);

        $sqlSelect = $table->getSql()->select();
        $sqlSelect->columns(['date']);
        $sqlSelect->join('artemis', 'artemis.id = artemis_occurrences.artemis_id', ['title']);
        $sqlSelect->where(['artemis_occurrences.request_id' => $id]);
        $artemis = $table->selectWith($sqlSelect);

        return new HtmlResponse($this->template->render('hermes::view', [
            'requests' => $requests,
            'nodes' => $nodes,
            'edges' => $edges,
            'max' => $maxCounter,
            'artemis' => $artemis,
            'lachesis' => $lachesis,
        ]));
    }
}
