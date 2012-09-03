<?php

namespace Soloist\Bundle\BaseAdminBundle\Form\Extension\Type;


use Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\Form\Extension\Core\Type\TimeType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\FormView,
    Symfony\Component\Form\FormInterface,
    Symfony\Bundle\FrameworkBundle\Translation\Translator;

use Symfony\Component\OptionsResolver\Options;

class JqueryTimeType extends TimeType
{
    /**
     * Symfony translator
     * @var \Symfony\Bundle\FrameworkBundle\Translation\Translator
     */
    protected $translator;
    /**
     * jQuery array option
     * @var array
     */
    protected $jQueryOptions;

    public function __construct($translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['widget'] = 'single_text';
        $this->jQueryOptions = $options['jquery_options'];

        parent::buildForm($builder, $options);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
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
        return 'soloist_jquery_time';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'jquery_options' => array(),
            'attr'              => array(
                'placeholder' => $this->translator->trans('soloist.admin.timepicker.placeholder')
            )
        ));
    }
}
