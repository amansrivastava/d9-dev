<?php

namespace Drupal\forex_rates\Service;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Http\ClientFactory;

/**
 * HTTP API Client to fetch Currency conversion.
 *
 * @package Drupal\forex_rates\Service
 */
class ForexRatesAPIClient {
  /**
   * HTTP Client.
   *
   * @var \GuzzleHttp\Client
   */
  private $client;

  /**
   * Stores API token, saved in config.
   *
   * @var token
   */
  private $token;

  /**
   * ForexRatesAPIClient Constructor.
   *
   * @param \Drupal\Core\Http\ClientFactory $http_client_factory
   *   HTTP client factory class.
   */
  public function __construct(ClientFactory $http_client_factory) {
    $config = \Drupal::config("forex_rates.settings");
    $endpoint = $config->get('api_url');
    $this->token = $config->get('api_token');
    $this->client = $http_client_factory->fromOptions([
      'base_uri' => $endpoint,
    ]);
  }

  /**
   * Fetch Forex Rates.
   *
   * @param string $base
   *   Base currency code.
   *
   * @return array
   *   API response
   */
  public function fetch(string $base) {
    $response = $this->client->get('', [
      'query' => [
        'base' => $base,
        'token' => $this->token,
      ],
    ]);
    return Json::decode($response->getBody());
  }

}
