<?php

namespace Rossible\GitHubProvider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Github;

/**
 * KnpLabs GitHub wrapper provider
 */
class GitHubServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['github.httpclient.cache.path'] = function ($app) {
            return sys_get_temp_dir();
        };

        $app['github.httpclient.cache'] = function ($app) {
            return new Github\HttpClient\Cache\FilesystemCache(
                $app['github.httpclient.cache.path']
            );
        };

        $app['github.httpclient.caching'] = true;
        $app['github.httpclient.options'] = [];
        $app['github.httpclient'] = function ($app) {
            if ($app['github.httpclient.caching']) {
                $client = Github\HttpClient\CachedHttpClient(
                    $app['github.httpclient.options']
                );
                $client->setCache($app['github.httpclient.cache']);
            } else {
                $client = new Github\HttpClient\HttpClient(
                    $app['github.httpclient.options']
                );
            }

            return $client;
        };

        $app['github.client'] = function ($app) {
            return new Github\Client($app['github.httpclient']);
        };
    }
}
