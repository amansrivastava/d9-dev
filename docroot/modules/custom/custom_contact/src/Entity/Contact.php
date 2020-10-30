<?php

namespace Drupal\custom_contact\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\custom_contact\ContactInterface;

/**
 * Defines the contact entity class.
 *
 * @ContentEntityType(
 *   id = "contact",
 *   label = @Translation("Contact"),
 *   label_collection = @Translation("Contacts"),
 *   handlers = {
 *     "view_builder" = "Drupal\custom_contact\ContactViewBuilder",
 *     "list_builder" = "Drupal\custom_contact\ContactListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\custom_contact\ContactAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\custom_contact\Form\ContactForm",
 *       "edit" = "Drupal\custom_contact\Form\ContactForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "contact",
 *   admin_permission = "administer contact",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/contact/add",
 *     "canonical" = "/contact/{contact}",
 *     "edit-form" = "/admin/content/contact/{contact}/edit",
 *     "delete-form" = "/admin/content/contact/{contact}/delete",
 *     "collection" = "/admin/content/contact"
 *   },
 *   field_ui_base_route = "entity.contact.settings"
 * )
 */
class Contact extends ContentEntityBase implements ContactInterface {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the contact entity.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setDescription(t('Contact email'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'email',
        'weight' => -4,
      ])
      ->setDisplayOptions('view', [
        'type' => 'email',
        'weight' => -4,
      ]);
    $fields['telephone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Telephone'))
      ->setDisplayOptions('form', [
        'type' => 'string',
        'weight' => -3,
      ])
      ->setDisplayOptions('view', [
        'type' => 'string',
        'weight' => -3,
      ]);
    $fields['address'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Address'))
      ->setDisplayOptions('form', [
        'type' => 'textarea',
        'weight' => -2,
      ])
      ->setDisplayOptions('view', [
        'type' => 'text',
        'weight' => -2,
      ]);
    return $fields;
  }

}
