<?php

namespace Drupal\custom_hook_init\EventSubscriber;

use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Custom Hook init event subscriber.
 */
class CustomHookInitSubscriber implements EventSubscriberInterface {

  /**
   * Current User.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $user;

  /**
   * Constructs event subscriber.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $user
   *   Current User.
   */
  public function __construct(AccountProxyInterface $user) {
    $this->user = $user;
  }

  /**
   * Add Access-Control-Allow-Origin header to unauthenticated users.
   *
   * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
   *   Response event.
   */
  public function addAccessAllowOriginHeaders(FilterResponseEvent $event) {
    if ($this->user->isAnonymous()) {
      $response = $event->getResponse();
      $response->headers->set('Access-Control-Allow-Origin', '*');
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::RESPONSE => ['addAccessAllowOriginHeaders'],
    ];
  }

}
