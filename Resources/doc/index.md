BaseAdminBundle
===============

*Attention, this bundle is only for Symfony 2.1.x*

How to install
--------------

###1) Install with composer

```JSON
    "require": {
    # Some others packages...

    # The base admin bundle
        "soloist/base-admin-bundle": "dev-master",
    },
```

Then type in your shell:

  composer.phar update

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

Do More
-------

  * Override the Form Handler to support your custom features
