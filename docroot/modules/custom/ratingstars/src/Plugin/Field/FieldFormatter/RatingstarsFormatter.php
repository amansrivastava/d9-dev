<?php

namespace Drupal\ratingstars\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'Star Rating' formatter.
 *
 * @FieldFormatter(
 *   id = "ratingstars",
 *   label = @Translation("Star Rating"),
 *   field_types = {
 *     "decimal"
 *   }
 * )
 */
class RatingstarsFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $rating = explode(".", $item->value);
      $stars = "";
      for ($i = 0; $i < 5; $i++) {
        if ($i < $rating[0]) {
          $stars .= "<i class='fa fa-star'></i>";
          continue;
        }
        if (round($rating[1] / 100)) {
          $stars .= "<i class=' fa-star-half-o'></i>";
          $rating[1] = 0;
          continue;
        }
        $stars .= "<i class='fa fa-star-o'></i>";
      }
      $element[$delta] = [
        '#markup' => $stars,
      ];
    }

    return $element;
  }

}
