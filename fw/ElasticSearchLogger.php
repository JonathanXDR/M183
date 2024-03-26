<?php
require 'vendor/autoload.php';

use Elastic\Elasticsearch\ClientBuilder;

class ElasticSearchLogger
{
    private $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->setHosts(['localhost:9200'])->build();
    }

    public function log($level, $message, $context = [])
    {
        $data = [
            'index' => 'application_logs',
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