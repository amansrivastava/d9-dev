<?php

namespace Drupal\forecast_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Forecast\Forecast;

/**
 * Provides a weather forecast block.
 *
 * @Block(
 *   id = "forecast_api_weather_forecast",
 *   admin_label = @Translation("Weather Forecast"),
 *   category = @Translation("Custom")
 * )
 */
class WeatherForecastBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'latitude' => "",
      'longitude' => "",
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['latitude'] = [
      '#type' => 'textfield',
      '#title' => $this->t('latitude'),
      '#default_value' => $config['latitude'] ?? '',
    ];
    $form['longitude'] = [
      '#type' => 'textfield',
      '#title' => $this->t('longitude'),
      '#default_value' => $config['longitude'] ?? '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['latitude'] = $form_state->getValue('latitude');
    $this->configuration['longitude'] = $form_state->getValue('longitude');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    $latitude = $config['latitude'] ?? '0';
    $longitude = $config['longitude'] ?? '0';

    // API key should not be in here - should be an env variable.
    $forecast = new Forecast('8d5093a7fa1fe90622af9b2f51c2a3dd');
    $report = $forecast->get(
      $latitude,
      $longitude,
      NULL,
      [
        'units' => 'si',
      ]
    );

    return [
      '#markup' => $this->t(
        'Forecast is @forecast with temperature of @temp deg C',
        [
          '@forecast' => $report->currently->summary,
          '@temp' => $report->currently->temperature,
        ]
      ),
    ];
  }

}
