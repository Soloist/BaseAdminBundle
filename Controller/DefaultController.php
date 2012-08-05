<?php

namespace Soloist\Bundle\BaseAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction()
    {
        $this->get('soloist_breadcrumbs')->add('Tableau de bord');

        return $this->render('SoloistBaseAdminBundle:Default:index.html.twig');
    }
}
