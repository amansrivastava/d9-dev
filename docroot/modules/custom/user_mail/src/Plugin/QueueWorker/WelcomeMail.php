<?php

namespace Drupal\user_mail\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Mail\MailManager;
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
   * Mail manager for sending mail.
   *
   * @var \Drupal\Core\Mail\MailManager
   */
  private $mailManager;

  /**
   * EntityManager Object for loading User.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  private $entityTypeManager;

  /**
   * {@inheritDoc}
   *
   * @param \Drupal\Core\Mail\MailManager $mailManager
   *   For sending mail.
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   *   For loading user object.
   */
  public function __construct(
    MailManager $mailManager,
    EntityTypeManager $entityTypeManager
  ) {
    $this->mailManager = $mailManager;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    $mailManager = \Drupal::service('plugin.manager.mail');
    $entityTypeManager = \Drupal::service('entity_type.manager');
    return new static($mailManager, $entityTypeManager);
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($userId) {
    /** @var \Drupal\user\Entity\User $user */
    $user = $this->entityTypeManager->getStorage('user')->load($userId);
    $config = \Drupal::config("system.site");
    $mail = $this->mailManager->mail(
      'user_mail',
      'welcome_mail',
      $user->getEmail(),
      $user->getPreferredLangcode(),
      ['message' => 'Welcome to ' . $config->get("name") . ', user ' . $user->getAccountName()],
      NULL,
      TRUE
    );

    $logger = \Drupal::logger('user_mail');
    if ($mail['result'] !== TRUE) {
      $logger->error('There was a problem sending the mail to ' . $user->getEmail());
    }
    else {
      $logger->info('The mail has been sent to ' . $user->getEmail());
    }

  }

}
