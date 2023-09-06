<?php

declare(strict_types=1);

namespace App\Providers;

use Elastic\Elasticsearch\{Client, ClientBuilder};
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;
use Matchish\ScoutElasticSearch\ElasticSearch\Config\Config;
use Matchish\ScoutElasticSearch\Engines\ElasticSearchEngine;

class ElasticSearchServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->app->bind(Client::class, function () {
            $clientBuilder = ClientBuilder::create()->setHosts(Config::hosts());
            /** @phpstan-ignore-next-line */
            if ($user = Config::user()) {
                /** @phpstan-ignore-next-line */
                $clientBuilder->setBasicAuthentication($user, Config::password());
            }
            /** @phpstan-ignore-next-line */
            if ($cloudId = Config::elasticCloudId()) {
                /** @phpstan-ignore-next-line  */
                $clientBuilder->setElasticCloudId($cloudId)->setApiKey(Config::apiKey());
            }
            if ($this->app->environment('testing')) {
                $clientBuilder->setSSLVerification(false);
            }

            return $clientBuilder->build();
        });

        resolve(EngineManager::class)->extend('elastic', function () {
            $elasticsearch = app(Client::class);

            return new ElasticSearchEngine($elasticsearch);
        });
    }
}
