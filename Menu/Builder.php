<?php

namespace Soloist\Bundle\BaseAdminBundle\Menu;


use Symfony\Component\DependencyInjection\ContainerAware,
    Symfony\Component\HttpFoundation\Request;

use Knp\Menu\FactoryInterface;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options = array())
    {
        $menu = $factory->createItem('root');
        $new = $menu->addChild('Nouveau');

        $this->container->get('event_dispatcher')->dispatch(
            Events::onConfigureTopMenu,
            new Event\Configure($menu)
        );

        $this->container->get('event_dispatcher')->dispatch(
            Events::onConfigureNewMenu,
            new Event\Configure($new)
        );

        return $menu;
    }

    public function sideMenu(FactoryInterface $factory, array $options  = array())
    {
        $menu = $factory->createItem('root');

        $global = $menu->addChild('Divers');
        $global->addChild('Tableau de bord', array('route' => 'soloist_base_admin_index'));

        $this->container->get('event_dispatcher')->dispatch(
            Events::onConfigureLeftMenu,
            new Event\Configure($menu)
        );

        return $menu;
    }
}
