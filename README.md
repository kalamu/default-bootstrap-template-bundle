# Default Bootstrap Template

This bundle is an example [Bootstrap](https://getbootstrap.com/) template for Kalamu. The 
original template has been generously published under the MIT licence on 
[HackerThemes](https://hackerthemes.com/bootstrap-themes/growth/) by _Alexander Rechsteiner_. 

Example screenshot
![Screenshot](screenshot.png)

# Installation

First you must install [Kalamu CMS](https://github.com/kalamu/kalamu#installation) then you can 
install this theme with composer :

``` bash
composer require kalamu/default-bootstrap-template-bundle
```

Register the bundle in `app/AppKernel.php` :

``` php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            //...
            new Kalamu\DefaultBootstrapTemplateBundle\KalamuDefaultBootstrapTemplateBundle(),
        ];
    }
}
```

Register the routing in `app/config/routing.yml` :

``` yml
# Add at the top of the file
kalamu_default_bootstrap_template_bundle:
    resource: "@KalamuDefaultBootstrapTemplateBundle/Resources/config/routing.yml"
    prefix:   /_fragment
```

Then you website should use the new template. If not check that the template configuration in
the `kalamu_cms_core` section of the main configuration (`app/config/config.yml`) has not 
been overrided.
