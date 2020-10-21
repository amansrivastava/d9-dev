<?php

namespace Drupal\update_logger\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure update_logger settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'update_logger_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['update_logger.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('update_logger.settings');
    $form['enable_content_log'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Logger on Content Update?'),
      '#default_value' => $config->get('enable_content_log'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('update_logger.settings')
      ->set('enable_content_log', $form_state->getValue('enable_content_log'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
