# Pimple GitHub API service provider

Provides the [KnpLabs GitHub API wrapper][wrapper] to Pimple (Silex, Cilex)
applications.

## Installation

1.  Composer require this package:

        composer require "rmasters/github-service-provider:~1.0"

2.  Register in your application:

    ```php
    $app = new Silex\Application;
    $app = new Pimple\Container;

    $app->register(new Rossible\GitHubProvider\GitHubServiceProvider);
    ```

This package requires Pimple 3.x and uses the ServiceProviderInterface and
Container interfaces/type-hints that it provides. Silex 2.0 supports this, and
Cilex should do soon.

## Configuration

You can modify the `GitHub\Client` construction by extending these services:

Service                      | Description                                                 | Default
-----------------------------|-------------------------------------------------------------|--------------------
github.client                | The Github\Client instance                                  | `Github\Client`
github.httpclient            | The [HttpClient][client] used for making requests           | `CachedHttpClient`
github.httpclient.caching    | When true, uses a CachedHttpClient                          | `true`
github.httpclient.options    | Options array passed to HttpClient                          | `[]`
github.httpclient.cache      | The [Response cache][cache] to use                          | `FilesystemCache`
github.httpclient.cache.path | When using FilesystemCache, where to store cached responses | `sys_get_temp_dir()`

To change these, [extend or replace the service][modifying-services], for
example:

```php
// Toggle the caching variable
$app['github.httpclient.caching'] = false;

// Use a subdirectory in the temp directory
$app->extend('github.httpclient.cache.path', function($path, $app) {
    $path .= '/github-responses';
    mkdir($path, 776);

    return $path;
});

// Set a custom Accept header to access pre-release features
$app->extend('github.httpclient', function (HttpClientInterface $httpclient, $app) {
    $httpclient->setHeaders(['Accept' => 'application/vnd.github.she-hulk-preview+json']);

    return $httpclient;
});
```

## License

Released under the [MIT License](LICENSE).

[wrapper]: https://github.com/knplabs/php-github-api
[client]: https://github.com/KnpLabs/php-github-api/tree/master/lib/Github/HttpClient
[cache]: https://github.com/KnpLabs/php-github-api/tree/master/lib/Github/HttpClient/Cache
[modifying-services]: http://pimple.sensiolabs.org/#modifying-services-after-definition
