<?php

namespace Drupal\custom_text\Plugin\Filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * Provides a 'Auto Capitalize' filter.
 *
 * @Filter(
 *   id = "custom_text_auto_capitalize",
 *   title = @Translation("Auto Capitalize"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_REVERSIBLE,
 *   settings = {
 *     "capitalize_words" = "",
 *   },
 *   weight = -10
 * )
 */
class AutoCapitalize extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form['capitalize_words'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Words to capitalize'),
      '#default_value' => $this->settings['capitalize_words'],
      '#description' => $this->t('List of words to capitalize (comma separated).'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    // @DCG Process text here.
    $words = array_map("trim", explode(",",$this->settings['capitalize_words']));

    foreach($words as $word){
      $text = str_replace($word, ucfirst($word), $text);
    }
    return new FilterProcessResult($text);
  }

}
