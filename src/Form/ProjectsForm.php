<?php

namespace Drupal\simple_gitlab_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\simple_gitlab_manager\ServiceGit;

/**
 * Provides a simple_gitlab_manager form.
 */
class ProjectsForm extends FormBase {

  private $target;

  private $target_id;

  private $action;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simple_gitlab_manager_projects';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
//    $form_state->setRedirect('<front>');
  }

}
