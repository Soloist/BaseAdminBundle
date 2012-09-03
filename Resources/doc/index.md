BaseAdminBundle
===============

*Attention, this bundle is only for Symfony 2.1.x*

How to install
--------------

###1) Install files
####a) Install with composer

```JSON
    "require": {
    # Some others packages...

    # The base admin bundle
        "soloist/base-admin-bundle": "dev-master",
    },
```

Then type in your shell:

  composer.phar update

###2) Add it to your app/AppKernel.php file:

```PHP
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ... Some bundle registrations ...

            // And the soloist base admin bundle
            new Soloist\Bundle\BaseAdminBundle\SoloistBaseAdminBundle(),
        );

        // ... More instructions and registrations ...
    }
}
```

###3) Add it to your route configuration

```YAML
SoloistBaseAdminBundle:
    resource: "@SoloistBaseAdminBundle/Resources/config/routing.xml"
    prefix:   /admin
```

###4) Install assets

```Shell
php app/console assets:install web/
```

###5) Add some files like... bootstrap
The Soloist base admin bundle is built with html totaly compatible with bootstrap.
So it's easy to have a clean administration without spend time on the design.

There is an example of files you can create, this example use less :

```Twig
{% extends 'SoloistBaseAdminBundle:Misc:headers.base.html.twig' %}
{# app/Resources/SoloistBaseAdminBundle/views/Misc/headers.html.twig #}
{% block soloist_base_admin_head %}

    {{ parent() }}
    {% stylesheets '../app/Resources/less/backend.less'
                   filter="less, ?yui_css" %}
        <link rel="stylesheet" type="text/css" href="{{ asset_url }}" />
    {% endstylesheets %}

{% endblock %}
```

```CSS
{# app/Resources/less/backend.less #}
@import '../bootstrap/less/bootstrap.less';

// This file is not include by default, because you're not forced to use less and the default design
@import '../../../vendor/soloist/base-admin-bundle/Soloist/Bundle/BaseAdminBundle/Resources/less/backend.less';
```

```Twig
{# app/Resources/SoloistBaseAdminBundle/views/Misc/javascripts.html.twig #}
{% extends 'SoloistBaseAdminBundle:Misc:javascripts.base.html.twig' %}
{% block soloist_base_admin_js %}
    {{ parent() }}

    {% javascripts
                    '%kernel.root_dir%/Resources/js/bootstrap/*.js'
        filter="?yui_js" %}
        <script type="text/javascript" src="{{ asset_url }}"></script>
    {% endjavascripts %}
{% endblock %}
```


How to use
----------

Here is an example for a simple entity "Product".

###1) A crudable entity

To be handled by the Dashboard, your entity must implement the CrudableInterface.

```PHP
// MyProject/MyBundle/Entity/Product.php
use Soloist\Bundle\BaseAdminBundle\Crud\CrudableInterface;

class Product implements CrudableInterface
{
    /**
     * {@inheritdoc}
     *
     */
    public function getRouteParams()
    {
        return array(
            'id' => $this->id
        );
    }
}
```

The method "getRouteParams" must return how to identify the entity.
This parameter will be pass to routes to controllers who need it.

Most of the time this method will return just an array who contains the id.

###2) Create your admin controller

You must create you "AdminController" who extends
[ORMCrudController](https://github.com/yohang/BaseAdminBundle/tree/master/Controller/ORMCrudController.php).

```PHP
// MyProject/MyBundle/Controller/AdminProductController.php

use MyProject\MyBundle\Entity\Product,
    MyProject\MyBundle\Form\ProductType;

// The Dashboard ORMCrudController component
use Soloist\Bundle\BaseAdminBundle\Controller\ORMCrudController;

class AdminProductController extends ORMCrudController
{
    /**
     * That's an example of configuration,
     * you can of course specify what you want in this configuration.
     *
     * @return array
     */
    protected function getParams()
    {
        return array(
            'display' => array(
                'id'        => array('label' => 'N°'),
                'name'      => array('label' => 'Name'),
                'price'     => array('label' => 'Price'),
                'description'    => array(
                    'label' => 'Description',
                    'type'  => 'longtext' // this option will reduce the text
                )
            ),
            'prefix'        => 'myapp_admin_product',
            'singular'      => 'Product',
            'plural'        => 'Products',
            'repository'    => 'MyProjectMyBundle:Product',
            'form_type'     => new ProductType,
            'class'         => new Product,
            'orderBy'       => array('price' => 'ASC')
        );
    }
}
```

For more informations, you should see the complete [options reference](reference.md) or directly the code of [the crud controller](../../Controller/ORMCrudController.php).

###3) Add the routes for dashboard

####a) First you must add the dashboard routes:

```JSON
SoloistBaseAdminBundle:
    resource: "@SoloistBaseAdminBundle/Resources/config/routing.xml"
    prefix:   /admin
```

*Be sure that the /admin is protected in your security configuration.*

####b) Then add the routes for your controller

```JSON
myapp_admin_product_index:
    pattern: /
    defaults: { _controller: "MyProjectMyBundle:AdminProduct:index" }

myapp_admin_product_new:
    pattern: /new
    defaults: { _controller: "MyProjectMyBundle:AdminProduct:new" }

myapp_admin_product_create:
    pattern: /create
    defaults: { _controller: "MyProjectMyBundle:AdminProduct:create" }
    requirements:
        _method: POST

myapp_admin_product_edit:
    pattern: /edit/{id}
    defaults: { _controller: "MyProjectMyBundle:AdminProduct:edit" }

myapp_admin_product_update:
    pattern: /update/{id}
    defaults: { _controller: "MyProjectMyBundle:AdminProduct:update" }
    requirements:
        _method: POST

myapp_admin_product_delete:
    pattern: /delete/{id}
    defaults: { _controller: "MyProjectMyBundle:AdminProduct:delete" }
```

If you don't precise routes in your configuration, you must precise a prefix.
The dashboard will add automaticly the good sufix.

###4) Add the listener

The listener is a service. Usually you create the directory "EventListener" and a class in it.

Look at this example:

```PHP
<?php

namespace MyProject\MyBundle\EventListener;

use Soloist\Bundle\BaseAdminBundle\Menu\Event\Configure;

/**
 * This class allow us to configure the menu of the administration
 */
class AdminListener
{
    public function onConfigureNewMenu(Configure $event)
    {
        $root = $event->getRoot();
        $root->addChild('Product', array(
            'route'           => 'myapp_admin_product_new'
        ));
    }

    public function onConfigureTopMenu(Configure $event)
    {
        $root = $event->getRoot();
        $root->addChild('Products', array('route' => 'myapp_admin_product_index'));
    }
}
```

Of course it don't work alone. You must add it in your buundle configuration as service. Here is an example with the xml syntax:

```XML
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">


    <parameters>
        <parameter key="myproject.mybundle.listener.menu.class">MyProject\MyBundle\EventListener\AdminListener</parameter>
    </parameters>

    <services>
        <service id="myproject.mybundle.listener.menu" class="%myproject.mybundle.listener.menu.class%">
            <tag name="kernel.event_listener" event="soloist_base_admin.configure.menu.new"  method="onConfigureNewMenu" />
            <tag name="kernel.event_listener" event="soloist_base_admin.configure.menu.top"  method="onConfigureTopMenu" />
        </service>
    </services>

</container>
```

Do More
-------

  * Override the Form Handler to support your custom features
