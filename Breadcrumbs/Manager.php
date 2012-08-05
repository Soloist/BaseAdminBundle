<?php

namespace Soloist\Bundle\BaseAdminBundle\Breadcrumbs;

use Symfony\Component\Routing\Router;

class Manager implements \IteratorAggregate
{
    protected $path = array();

    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function add($item, $params = array())
    {
        if (!$item instanceof Item) {
            $item = new Item($item, $params);
        }

        $item->setRouter($this->router);
        $this->path[] = $item;

        return $this;
    }

    public function prepend($item, $params = array())
    {
        if (!$item instanceof Item) {
            $item = new Item($item, $params);
        }

        $item->setRouter($this->router);
        array_unshift($this->path, $item);

        return $this;
    }

    public function all()
    {
        return $this->path;
    }

    /*
     * IteratorAggregate methods
     */

    public function getIterator()
    {
        return new \ArrayIterator($this->path);
    }
}
