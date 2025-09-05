<?php

namespace brikdigital\lerendoejenu\services;

use brikdigital\lerendoejenu\LerenDoeJeNu;
use craft\helpers\App;
use GuzzleHttp\Client;
use yii\base\Component;

class LerenDoeJeNuApiService extends Component
{
    private Client $client;

    public function __construct($config = [])
    {
        $settings = LerenDoeJeNu::getInstance()->getSettings();
        $this->client = new Client([
            'base_uri' => rtrim(App::parseEnv($settings->apiUrl), '/') . '/external/',
            'headers' => [
                'api-key' => App::parseEnv($settings->apiKey),
            ]
        ]);

        parent::__construct($config);
    }

    public function get(string $endpoint, array $query = []): array
    {
        $endpoint = ltrim($endpoint, '/');

        $response = $this->client->get($endpoint, ['query' => $query]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getAll(string $endpoint, array $query = [], callable $pageCallback = null): array
    {
        $response = $this->get($endpoint, $query);

        if (is_callable($pageCallback)) {
            $pageCallback($response);
        }

        if (isset($response['page'])) {
            // Zero-indexed
            $page = $response['page']['number'] + 1;
            $last = $response['page']['total_pages'];

            if ($page < $last) {
                $query['page'] = $page;
                $nextPage = $this->getAll($endpoint, $query, $pageCallback);
                $response['content'] = array_merge($response['content'], $nextPage);
            }
        }

        return $response['content'];
    }
}