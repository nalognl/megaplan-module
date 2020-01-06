<h1 align="center">Megaplan module</h1>

Cache, authentication and request handlers for nalognl_megaplan and nalognl_new_deal plugins.

## Load configurations

Load configurations in your boot file by calling `load()` method.

```php
\Nalognl\MegaplanModule\Config::new()->load([
    'plugin_path' => '/var/www/html/wp-content/plugins/nalognl_megaplan',
    'megaplan_host' => getenv('NNND_HOST'),
]);
```

## Plugin must have

Plugin must have directories `storage` and `storage/cache`.

## Get started

```bash
composer require nalognl/megaplan-module
```