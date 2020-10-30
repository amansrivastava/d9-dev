<?php

namespace Drupal\custom_contact;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining a contact entity type.
 */
interface ContactInterface extends ContentEntityInterface {

  /**
   * Gets the contact name.
   *
   * @return string
   *   Name of the contact.
   */
  public function getName();

  /**
   * Sets the contact name.
   *
   * @param string $name
   *   The contact name.
   *
   * @return \Drupal\custom_contact\ContactInterface
   *   The called contact entity.
   */
  public function setName($name);

}
