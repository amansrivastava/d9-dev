<?php

namespace Drupal\forecast_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Forecast\Forecast;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a weather forecast block.
 *
 * @Block(
 *   id = "forecast_api_weather_forecast",
 *   admin_label = @Translation("Weather Forecast"),
 *   category = @Translation("Custom")
 * )
 */
class WeatherForecastBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * WeatherForecastBlock constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config object.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('config.factory'));
  }

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
    $config = $this->configFactory->get("forecast_api.settings");

    // API key should not be in here - should be an env variable.
    $forecast = new Forecast($config->get('api_key'));
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
