<?php

namespace Soloist\Bundle\BaseAdminBundle\Breadcrumbs;

use Symfony\Component\Routing\Router;

class Item
{
    protected $title;

    protected $params = array();

    /**
     * @var \Symfony\Component\Routing\Router
     */
    protected $router;

    public function __construct($title = '', $params = array())
    {
        $this->title  = $title;
        $this->params = $params;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    public function getUri()
    {
        if (!isset($this->params['uri'])) {
            if (!isset($this->params['route'])) {
                return null;
            }

            $this->params['uri'] = $this->router->generate(
                $this->params['route'],
                isset($this->params['route_params']) ? $this->params['route_params'] : array()
            );
        }

        return $this->params['uri'];
    }
}
