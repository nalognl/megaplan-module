<h1 align="center">Megaplan module WordPress plugin module</h1>

[![Build Status](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Factions-badge.atrox.dev%2Fnalognl%2Fmegaplan-module%2Fbadge&style=flat)](https://actions-badge.atrox.dev/nalognl/megaplan-module/goto)
[![Total Downloads](https://poser.pugx.org/nalognl/megaplan-module/downloads)](https://packagist.org/packages/nalognl/megaplan-module)
[![Latest Stable Version](https://poser.pugx.org/nalognl/megaplan-module/v/stable)](https://packagist.org/packages/nalognl/megaplan-module)

Cache, authentication and request handlers for `nalognl_megaplan` and `nalognl_new_deal` **WordPress** plugins. These 2 plugins have common functionality, and in order to not repeat the same code in both plugins this package was created. It contains **Megaplan authentication functionality**, **base 64 encoding** and *caching*.

## Load configurations

Load configurations in your boot file by calling `load()` method. These parameters are required for package to work.
Also your WordPress plugin must have directories `storage` and `storage/cache`.

```php
\Nalognl\MegaplanModule\Config::new()->load([
    'plugin_path' => '/var/www/html/wp-content/plugins/nalognl_megaplan',
    'megaplan_host' => getenv('NNND_HOST'),
    'megaplan_login' => getenv('NNND_LOGIN'),
    'megaplan_password' => getenv('NNND_PASSWORD'),
    'megaplan_hash_password' => getenv('NNND_MD5_HASH_PASSWORD'),
    'megaplan_api1_auth_uri' => getenv('NNND_AUTH_URI'),
    'megaplan_api3_auth_uri' => getenv('NNND_API3_AUTH_URI'),
]);
```

## Usage

#### Megaplan request

You need to have RequestMegaplan1.php and RequestMegaplan3.php for both APIs. They must extend `Nalognl\MegaplanModule\Http\RequestMegaplan\RequestMegaplan` class, it gives you `throwIfError()` and `getEnvOrThrow()` methods, and most importantly request property with methods: `post()` and `get()`. Most of your methods in extending classes will look something like that:

```php
public function getOffers(array $data): array
{
    $uri = $this->getEnvOrThrow('NNND_OFFERS_LIST_URI');

    $res = $this->request->post($uri, $data);
    $this->throwIfError($res, 'API1: При попытке взять товары с мегаплана');

    return $res->data->offers;
}
```

## Get started

To install the package you need to have [Composer PHP package manager](https://getcomposer.org) installed on your machine. Then you need to run the command below in the plugin directory.

```bash
composer require nalognl/megaplan-module
```