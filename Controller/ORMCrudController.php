<?php

namespace Soloist\Bundle\BaseAdminBundle\Controller;

use Soloist\Bundle\BaseAdminBundle\Crud\Form\Handler;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\Request;


abstract class ORMCrudController extends Controller
{
    /**
     * Paramaters available for personalize your backend
     * Templates management parameters (all optional):
     *  - indexTemplate
     *  - newTemplate
     *  - editTemplate
     *  - formTemplate
     *
     * Routes management parameters:
     * (notice that if you fill the "prefix" parameter
     * you don't have to fill all routes)
     *  - indexRoute
     *  - newRoute
     *  - createRoute
     *  - editRoute
     *  - updateRoute
     *  - deleteRoute
     *  - prefix
     *
     *  Form management parameters:
     *   - form_type
     *
     *  Entities management parameters:
     *   - class
     *   - repository
     *
     *  Speeling management parameters:
     *   - singular
     *   - plural
     *   - feminine (boolean)
     *   - display (an array)
     *   - createSentence
     *   - updateSentence
     *   - deleteSentence
     *
     *  Sort management parameters:
     *   - orderBy (array)
     *   - sortable (boolean)
     *
     * @var array
     */
    protected static $params = array(
        'indexTemplate'  => 'SoloistBaseAdminBundle:Crud:index.html.twig',
        'newTemplate'    => 'SoloistBaseAdminBundle:Crud:new.html.twig',
        'editTemplate'   => 'SoloistBaseAdminBundle:Crud:edit.html.twig',
        'formTemplate'   => 'SoloistBaseAdminBundle:Crud:form.html.twig',
        'indexRoute'     => null,
        'newRoute'       => null,
        'createRoute'    => null,
        'editRoute'      => null,
        'updateRoute'    => null,
        'deleteRoute'    => null,
        'form_type'      => null,
        'class'          => null,
        'repository'     => null,
        'object_actions' => array(),
        'singular'       => 'objet',
        'plural'         => 'objets',
        'feminine'       => false,
        'display'        => array('id' => array('label' => 'N°')),
        'orderBy'          => null,
        'createSentence' => null,
        'updateSentence' => null,
        'deleteSentence' => null,
        'sortable'       => false,
        'globalTitle'    => 'Soloist Base Admin'
    );

    /**
     * Parameters var used by the class
     * @var array
     */
    protected $mergedParams = null;


    /**
     * List Action
     *
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     */
    public function indexAction()
    {
        $this->addBaseBreadcrumb(false);

        $request = $this->container->get('request');
        $key = $request->query->get('key');
        $direction = $request->query->get('direction');
        $objects = null;

        if ($key != null && $direction != null && in_array($direction, array('asc', 'desc'))) {
            $objects = $this->getRepository()
                ->findBy(
                    array(),
                    array($key => strtoupper($direction))
                )
            ;
        } else {
            $objects = $this->getRepository()
                ->findBy(
                    array(),
                    $this->getParam('orderBy')
                )
            ;
        }

        return $this->render($this->getParam('indexTemplate'), array(
            'objects'       => $objects,
            'currentSort'   => $key
        ));
    }

    /**
     * New action, displays create form
     *
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     */
    public function newAction()
    {
        $this->addBaseBreadcrumb()->add('Nouveau');

        return $this->render($this->getParam('newTemplate'), array(
            'form'   => $this->getFormHandler()->getForm($this->getFormType(), $this->createObject())->createView()
        ));
    }

    /**
     * Edit action, displays update form
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     */
    public function editAction(Request $request)
    {
        $this->addBaseBreadcrumb()->add('Modifier');
        $entity = $this->findEntity($request);

        return $this->render($this->getParam('editTemplate'), array(
            'form'   => $this->getFormHandler()->getForm($this->getFormType(), $entity)->createView(),
            'object' => $entity
        ));
    }

    /**
     * Create action, computes create form
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $this->addBaseBreadcrumb()->add('Nouveau');
        $handler = $this->getFormHandler();
        $form = $handler->getForm($this->getFormType(), $this->createObject());

        if ($handler->create($form, $request)) {
            $this->get('session')->getFlashBag()->set('success', $this->getParam('createSentence'));

            return $this->redirectIndex();
        }

        return $this->render($this->getParam('newTemplate'), array('form' => $form->createView()));
    }

    /**
     * Update action, computes update form
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $this->addBaseBreadcrumb()->add('Modifier');
        $entity  = $this->findEntity($request);
        $handler = $this->getFormHandler();
        $form    = $handler->getForm($this->getFormType(), $entity);

        if ($handler->update($form, $request)) {
            $this->get('session')->getFlashBag()->set('success', $this->getParam('updateSentence'));

            return $this->redirectIndex();
        }

        return $this->render($this->getParam('editTemplate'), array(
            'form'      => $form->createView(),
            'object'    => $entity
        ));
    }

    /**
     * Delete action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $entity = $this->findEntity($request);
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->set('success', $this->getParam('deleteSentence'));

        return $this->redirectIndex();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Soloist\Bundle\BaseAdminBundle\Crud\CrudableInterface
     */
    protected function findEntity(Request $request)
    {
        $entity = $this->getRepository()->find($request->attributes->get('id'));
        if(!$entity) {
            throw $this->createNotFoundException(sprintf(
                'The entity "%s" searched by the BaseAdminBundle with id "%s" was not found.',
                $this->getParam('class'),
                $request->attributes->get('id')
            ));
        }

        return $entity;
    }

    /**
     * @return \Soloist\Bundle\BaseAdminBundle\Crud\Form\Handler
     */
    protected function getFormHandler()
    {
        return new Handler($this->getDoctrine()->getEntityManager(), $this->get('form.factory'));
    }

    /**
     * Merge params and returns them
     *
     * @return array
     */
    protected function getMergedParams()
    {
        if (null !== $this->mergedParams) {
            return $this->mergedParams;
        }

        $params = array_merge(self::$params, $this->getParams());
        if (isset($params['prefix'])) {
            foreach (array('index', 'new', 'create', 'edit', 'update', 'delete') as $action) {
                $params[$action.'Route'] = $params['prefix'].'_'.$action;
            }
        }

        // Spelling articles
        $startVoyel = in_array(strtolower($params['singular'][0]), array('a', 'e', 'i', 'o', 'u', 'y'));
        $params['articles'] = array(
            'singular' => array(
                'undefined' => $params['feminine'] ? 'une' : 'un',
                'defined'   => $startVoyel ? 'l\'' : ($params['feminine'] ? 'la' : 'le'),
            ),
            'plural' => array('undefined' => 'des', 'defined' => 'les')
        );

        foreach (array(
            'createSentence' => '%s %s a été créé%s avec succès.',
            'updateSentence' => '%s %s a été modifié%s avec succès.',
            'deleteSentence' => '%s %s a été supprimé%s avec succès.'
        ) as $key => $sentence) {
            $params[$key] = $params[$key] ?: sprintf(
                $sentence,
                $params['articles']['singular']['defined'],
                $params['singular'],
                $params['feminine'] ? 'e' : ''
            );
        }


        $title = $this->container->getParameter('soloist_base_admin_title');
        $params['globalTitle'] = $title;

        return $this->mergedParams = $params;
    }

    /**
     * returns a merged param
     *
     * @param $name
     * @return mixed
     */
    protected function getParam($name)
    {
        $this->getMergedParams();

        return $this->mergedParams[$name];
    }

    /**
     * Overrides the base controller render, and add the params to view
     *
     * @{inheritDoc}
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $parameters['params'] = $this->getMergedParams();

        return parent::render($view, $parameters, $response);
    }

    public function redirectIndex()
    {
        return $this->redirect($this->generateUrl($this->getParam('indexRoute')));
    }

    /**
     * @return \Soloist\Bundle\BaseAdminBundle\Breadcrumbs\Manager
     */
    protected function addBaseBreadcrumb($linkList = true)
    {
        $params = array();
        if (true === $linkList) {
            $params  = array('route' => $this->getParam('indexRoute'));
        }

        return $this->get('soloist_breadcrumbs')
            ->add('Tableau de bord', array('route' => 'soloist_base_admin_index'))
            ->add('Gestion des '.$this->getParam('plural'), $params)
        ;
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    protected function getFormType()
    {
        return $this->getParam('form_type');
    }

    /**
     * @return \Soloist\Bundle\BaseAdminBundle\Crud\CrudableInterface
     */
    protected function createObject()
    {
        return $this->getParam('class');
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository($this->getParam('repository'));
    }

    /**
     * @abstract
     * @return array
     */
    abstract protected function getParams();
}
