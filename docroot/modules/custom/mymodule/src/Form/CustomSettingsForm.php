<?php

namespace Drupal\mymodule\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure mymodule settings for this site.
 */
class CustomSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mymodule_custom_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['mymodule.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('mymodule.settings');
    $form['custom_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom Name'),
      '#default_value' => $config->get('custom_name'),
    ];
    $form['custom_checkbox'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('This is a checkbox example.'),
      '#default_value' => $config->get('custom_checkbox'),
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Custom Configs'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('mymodule.settings')
      ->set('custom_name', $form_state->getValue('custom_name'))
      ->set('custom_checkbox', $form_state->getValue('custom_checkbox'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
