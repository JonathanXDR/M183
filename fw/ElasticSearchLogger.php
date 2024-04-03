<?php
$basePath = dirname(__DIR__, 1);

require_once $basePath . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($basePath);
$dotenv->load();

use Elastic\Elasticsearch\ClientBuilder;

class ElasticSearchLogger
{
    private $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([$_ENV['ELASTICSEARCH_HOST'] . ':' . $_ENV['ELASTICSEARCH_PORT']])
            ->setBasicAuthentication($_ENV['ELASTIC_USERNAME'], $_ENV['ELASTIC_PASSWORD'])
            ->build();
    }

    public function log($level, $message, $context = [])
    {
        $data = [
            'index' => 'logs-' . date('d.m.Y'),
            'body' => [
                'level' => $level,
                'message' => $message,
                'context' => $context,
                'timestamp' => date('c'),
            ]
        ];
        $this->client->index($data);
    }
}
?>