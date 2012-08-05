Dashboard bundle reference
==========================

This reference explain options you can use to parameter your admin entity.

###1) What options you *must* precise

```PHP
protected function getParams()
{
    return array(
        'display' => array(
            'id'        => array('label' => 'N°'),      // The label is the column name
            'name'      => array('label' => 'Name'),    // The key is the entity field
            'price'     => array('label' => 'Price'),
            'description'    => array('label' => 'Description')
        ),
        'prefix'        => 'myapp_admin_product',
        'singular'      => 'Product',
        'plural'        => 'Products',
        'repository'    => 'MyProjectMyBundle:Product',
        'form_type'     => new ProductType,
        'class'         => new Product,
    );
}

```


###2) Customize templates
Defaults templates are dashboard crud templates. You can precise yours:

```PHP
// ...
    'indexTemplate'  => 'FrequenceWebBaseAdminBundle:Crud:index.html.twig',
    'newTemplate'    => 'FrequenceWebBaseAdminBundle:Crud:new.html.twig',
    'editTemplate'   => 'FrequenceWebBaseAdminBundle:Crud:edit.html.twig',
    'formTemplate'   => 'FrequenceWebBaseAdminBundle:Crud:form.html.twig',
// ...
```

###3) Use specific routes
By default you must only precise the option "prefix". The bundle will add automatically suffix (like _index, _new, _create...).
But if you want, you can customize each route.

```PHP
    'indexRoute'     => null,
    'newRoute'       => null,
    'createRoute'    => null,
    'editRoute'      => null,
    'updateRoute'    => null,
    'deleteRoute'    => null,
```

