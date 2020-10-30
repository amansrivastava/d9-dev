<?php

namespace Drupal\custom_contact\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the contact entity edit forms.
 */
class ContactForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New contact %label has been created.', $message_arguments));
      $this->logger('custom_contact')->notice('Created new contact %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The contact %label has been updated.', $message_arguments));
      $this->logger('custom_contact')->notice('Updated new contact %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.contact.canonical', ['contact' => $entity->id()]);
  }

}
