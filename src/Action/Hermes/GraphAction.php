<?php
namespace Zeus\Action\Hermes;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class GraphAction
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
        $graph = $request->getAttribute('graph');
        $project = $request->getAttribute('project');

        $table = new TableGateway('access',$this->adapter);
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

        return new HtmlResponse($this->template->render('hermes::'.$graph, [
            'data' => [
                'nodes' => $nodes,
                'edges' => $edges,
                'max' => $maxCounter,
            ],
        ]));
    }
}
