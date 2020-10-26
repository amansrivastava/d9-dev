<?php


namespace Drupal\forex_rates\Service;

use Drupal\Component\Serialization\Json;

/**
 * Class ForexRatesAPIClient
 * @package Drupal\forex_rates\Service
 */
class ForexRatesAPIClient
{
  /** @var \GuzzleHttp\Client $client */
  private $client;

  private $token;

  public function __construct($http_client_factory)
  {
    $config = \Drupal::config("forex_rates.settings");
    $endpoint = $config->get('api_url');
    $this->token = $config->get('api_token');
    $this->client = $http_client_factory->fromOptions([
      'base_uri' => $endpoint
    ]);
  }

  public function fetch(string $base)
  {
    $response = $this->client->get('', [
      'query' => [
        'base' => $base,
        'token' => $this->token
      ]
    ]);
    return Json::decode($response->getBody());
  }



}
