<?php

namespace Drupal\custom_contact\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Custom Contact routes.
 */
class CustomContactController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
