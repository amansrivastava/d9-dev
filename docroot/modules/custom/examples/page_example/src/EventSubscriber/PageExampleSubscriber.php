<?php

namespace Drupal\page_example\EventSubscriber;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Page Example event subscriber.
 */
class PageExampleSubscriber implements EventSubscriberInterface {

  /**
   * Logger Factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * PageExampleSubscriber constructor.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerFactory
   *   Logger Factory object.
   */
  public function __construct(LoggerChannelFactoryInterface $loggerFactory) {
    $this->loggerFactory = $loggerFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      'simple_page_load' => 'logPageLoad',
    ];
  }

  /**
   * Add log on page load.
   */
  public function logPageLoad() {
    $this->loggerFactory->get("event_subscriber")->debug("simple_page_load subscriber logged.");
  }

}
