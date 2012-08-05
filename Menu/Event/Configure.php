<?php

namespace Soloist\Bundle\BaseAdminBundle\Menu\Event;

use Symfony\Component\EventDispatcher\Event;

use Knp\Menu\ItemInterface;

class Configure extends Event
{
    /**
     * @var \Knp\Menu\ItemInterface
     */
    protected $root;

    public function __construct(ItemInterface $item)
    {
        $this->root = $item;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function getRoot()
    {
        return $this->root;
    }
}
