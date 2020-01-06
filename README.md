<h1 align="center">Megaplan module WordPress plugin module</h1>

Cache, authentication and request handlers for nalognl_megaplan and nalognl_new_deal plugins.

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

You need to have RequestMegaplan1.php and RequestMegaplan3.php for both APIs. They must extend `Nalognl\MegaplanModule\Http\RequestMegaplan\RequestMegaplan` class, it gives you `post()`, `get()` and `throwIfError()` methods. Your methods will look something like that:

```php
public function getOffers(array $data): array
{
    $uri = getenv('NNND_OFFERS_LIST_URI');

    $res = $this->request->post($uri, $data);
    $this->throwIfError($res, 'API1: При попытке взять товары с мегаплана');

    return $res->data->offers;
}
```

## Get started

```bash
composer require nalognl/megaplan-module
```