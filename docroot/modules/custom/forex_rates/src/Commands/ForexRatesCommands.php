<?php

namespace Drupal\forex_rates\Commands;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\forex_rates\Service\ForexRatesAPIClient;
use Drush\Commands\DrushCommands;

/**
 * Drush command for updating Forex Rates.
 */
class ForexRatesCommands extends DrushCommands {

  /**
   * Entity Manager object for loading Custom Blocks.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  private $entityTypeManager;

  /**
   * HTTP CLient.
   *
   * @var \GuzzleHttp\Client
   */
  private $forexClient;

  /**
   * Constructor.
   */
  public function __construct(
    EntityTypeManager $entityTypeManager,
    ForexRatesAPIClient $forexApiClient) {
    $this->entityTypeManager = $entityTypeManager;
    $this->forexClient = $forexApiClient;
  }

  /**
   * Update all forex_rates custom block with updated rates.
   *
   * @command forex_rates:update_all
   * @aliases uprates
   */
  public function updateAllForex() {
    $rateCards = $this->entityTypeManager->getStorage('block_content')
      ->loadByProperties([
        'type' => 'forex_rates',
      ]);

    foreach ($rateCards as $card) {
      $base = $card->get('field_base_currency')->entity->getName();
      $data = $this->forexClient->fetch($base);
      $targetCurrency = $card->get('field_target_currency')->referencedEntities();
      $rates = "";
      foreach ($targetCurrency as $curr) {
        $rates .= sprintf("%s: %s \n", $curr->getName(), $data["quote"][$curr->getName()]);
      }
      $card->set('field_forex_rates', $rates);
      $card->save();
    }

    $this->output()->writeln('All cards are updated!');
  }

}
