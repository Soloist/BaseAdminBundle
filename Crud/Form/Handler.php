<?php

namespace Soloist\Bundle\BaseAdminBundle\Crud\Form;

use Soloist\Bundle\BaseAdminBundle\Crud\CrudableInterface;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Form\FormTypeInterface,
    Symfony\Component\Form\FormFactory,
    Symfony\Component\Form\Form;

use Doctrine\ORM\EntityManager;

class Handler
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    protected $formFactory;

    public function __construct(EntityManager $em, FormFactory $formFactory)
    {
        $this->em          = $em;
        $this->formFactory = $formFactory;
    }

    public function getForm(FormTypeInterface $type, CrudableInterface $object)
    {
        return $this->formFactory->create($type, $object);
    }

    public function create(Form $form, Request $request)
    {
        $form->bindRequest($request);
        if ($form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();

            return true;
        }

        return false;
    }

    public function update(Form $form, Request $request)
    {
        $form->bindRequest($request);
        if ($form->isValid()) {
            $this->em->flush();

            return true;
        }

        return false;
    }

}
