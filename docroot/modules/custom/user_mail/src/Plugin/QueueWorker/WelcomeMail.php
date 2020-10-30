<?php

namespace Drupal\user_mail\Plugin\QueueWorker;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines 'user_mail_welcomemail' queue worker.
 *
 * @QueueWorker(
 *   id = "user_mail_welcomemail",
 *   title = @Translation("WelcomeMail"),
 *   cron = {"time" = 60}
 * )
 */
class WelcomeMail extends QueueWorkerBase implements ContainerFactoryPluginInterface {
  
  /**
   * The user storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $storage;

  /**
   * A logger instance.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $logger;

  /**
   * The mail manager.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritDoc}
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   *   The mail manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    LoggerChannelFactoryInterface $logger,
    MailManagerInterface $mail_manager,
    ConfigFactoryInterface $config_factory
  ) {
    $this->storage = $entity_type_manager->getStorage('user');
    $this->logger = $logger;
    $this->mailManager = $mail_manager;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('logger.factory')->get('user_mail'),
      $container->get('plugin.manager.mail'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($userId) {
    /** @var \Drupal\user\Entity\User $user */
    $user = $this->storage->load($userId);
    $config = $this->configFactory->get("system.site");
    $mail = $this->mailManager->mail(
      'user_mail',
      'welcome_mail',
      $user->getEmail(),
      $user->getPreferredLangcode(),
      ['message' => 'Welcome to ' . $config->get("name") . ', user ' . $user->getAccountName()],
      NULL,
      TRUE
    );

    if ($mail['result'] !== TRUE) {
      return $this->logger->error('There was a problem sending the mail to ' . $user->getEmail());
    }
    return $this->logger->info('The mail has been sent to ' . $user->getEmail());

  }

}
