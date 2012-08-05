<?php

namespace Soloist\Bundle\BaseAdminBundle\Form\Extension\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\Form\Extension\Core\Type\DateType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\FormViewInterface,
    Symfony\Component\Form\FormInterface;

class JqueryDateType extends DateType
{
    protected $jQueryOptions;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['widget'] = 'choice';
        $options['days']   = range(1, 31);
        $this->jQueryOptions = $options['jquery_options'];

        parent::buildForm($builder, $options);
    }

    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $view->setVar('jquery_options', $this->jQueryOptions);
        $view->setVar('uid', uniqid());
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'soloist_jquery_date';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array('jquery_options' => array()));
    }
}
