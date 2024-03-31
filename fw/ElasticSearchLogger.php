<?php
$basePath = dirname(__DIR__, 1);

require_once $basePath . '/vendor/autoload.php';

use Elastic\Elasticsearch\ClientBuilder;

class ElasticSearchLogger
{
    private $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(['elasticsearch:9200'])
            ->setBasicAuthentication('elastic', 'elastic')
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