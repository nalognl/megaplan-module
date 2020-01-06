<h1 align="center">Megaplan module</h1>

Cache, authentication and request handlers for nalognl_megaplan and nalognl_new_deal plugins.

## Load configurations

Load configurations in your boot file by calling `load()` method.

```php
\Nalognl\MegaplanModule\Config::getInstance()->load([
    'plugin_path' => '/var/www/html/wp-content/plugins/nalognl_megaplan',
]);
```

## Get started

```bash
composer require nalognl/megaplan-module
```