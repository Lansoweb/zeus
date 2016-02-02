<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class SelectProject extends AbstractHelper
{
    public function __invoke()
    {
        $sm = $this->getView()->getHelperPluginManager()->getServiceLocator();

        $config = $sm->get('Config');

        if (!isset($config['zeus']['projects']) || empty($config['zeus']['projects'])) {
            return;
        }

        $urlHelper = $this->getView()->getHelperPluginManager()->get('url');
        //$serverUrlHelper = $this->getView()->getHelperPluginManager()->get('serverurl');

        $projects = $config['zeus']['projects'];

        $routeMatch = $sm->get('Application')->getMvcEvent()->getRouteMatch();
        $selected = $routeMatch->getParam('project',0);

        $selectedName = 'Select a project';
        foreach ($projects as $name => $id) {
            if ($id == $selected) {
                $selectedName = $name;
            }
        }

        $ret = <<<EOF
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">$selectedName <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
EOF;
        foreach ($projects as $name => $id) {
            $ret .= '<li'.($id == $selected ? ' class="active"' : '').'><a href="'.$urlHelper(null, ['project' => $id]).'">'.$name.'</a></li>';
        }
        $ret .= '</ul></li>';

        return $ret;
    }
}
