<?php

namespace Soloist\Bundle\BaseAdminBundle\Breadcrumbs;


class Extension extends \Twig_Extension
{
    /**
     * @var \Soloist\Bundle\BaseAdminBundle\Breadcrumbs\Manager
     */
    private $manager;

    /**
     * @var \Twig_Environment
     */
    private $templating;

    public function __construct(Manager $manager)
    {
        $this->manager    = $manager;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->templating = $environment;
    }

    public function getFunctions()
    {
        return array(
            'breadcrumbs_render' => new \Twig_Function_Method($this, 'render', array('is_safe' => array('html'))),
        );
    }

    public function render($template = 'SoloistBaseAdminBundle::breadcrumbs.html.twig')
    {
        return $this->templating->loadTemplate($template)->renderBlock('breadcrumbs', array('breadcrumbs' => $this->manager));
    }

    public function getName()
    {
        return 'soloist_breadcrumbs';
    }
}
