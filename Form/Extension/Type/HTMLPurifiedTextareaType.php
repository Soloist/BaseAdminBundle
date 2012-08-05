<?php

namespace Soloist\Bundle\BaseAdminBundle\Form\Extension\Type;

use Symfony\Component\Form\Extension\Core\Type\TextareaType,
    Symfony\Component\Form\FormBuilderInterface;

use Exercise\HTMLPurifierBundle\Form\HTMLPurifierTransformer;

class HTMLPurifiedTextareaType extends TextareaType
{
    protected $purifierTransformer;

    public function __construct(HTMLPurifierTransformer $transformer)
    {
        $this->purifierTransformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addModelTransformer($this->purifierTransformer);
    }

    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'html_purified_textarea';
    }
}
