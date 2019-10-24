<?php

namespace Drupal\simple_gitlab_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\simple_gitlab_manager\ServiceGit;


/**
 * Provides a simple_gitlab_manager form.
 */
class GroupsForm extends FormBase {

  private $target;

  private $target_id;

  private $action;

  private $parent;


  /**
   * @var \Drupal\simple_gitlab_manager\ServiceGit
   */
  private $api_action;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simple_gitlab_manager_groups';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    //                unset($_SESSION['simple_gitlab_manager']['targets']);
    $this->target = &$_SESSION['simple_gitlab_manager']['targets']['target'];
    $this->target_id = &$_SESSION['simple_gitlab_manager']['targets']['target_id'];
    $this->action = &$_SESSION['simple_gitlab_manager']['targets']['action'];
    $this->parent = &$_SESSION['simple_gitlab_manager']['targets']['parent'];

    /**
     * ACTIONS FOR BUTTONS
     */
    if ($this->action == 'DELETE') {
      $path = $this->target . '' . $this->target_id . '' . $this->action;
      $ex = new ServiceGit($this->target, $this->target_id, $this->action);

      if ($this->target == '/projects') {
        $this->target = '/groups';
        $this->target_id = $this->parent;
      }
      else {
        $this->target = '/groups';
        $this->target_id = NULL;
      }
      $this->action = 'GET';
      $this->api_action = new ServiceGit($this->target, $this->target_id, $this->action);

    }
    else {
      $this->api_action = new ServiceGit($this->target, $this->target_id, $this->action);
    }

    $arr_git = $this->api_action->arr;


    /**
     * ADD THEME FROM MODULE
     */
    $form['#theme'] = 'simple_gitlab_manager';
    /**
     * ADD BUTTON BACK
     */
    if (strripos($this->target_id, '/projects')) {
      $form['back'] = [
        '#type' => 'submit',
        '#value' => 'Back',
        '#attributes' => ['class' => ['btn_back']],

      ];
    }

    /**
     * EMPTY ALERT
     */
    if (!count($arr_git)) {
      $form[] = [
        $item[] = [

          '#type' => 'item',
          '#title' => 'The GITLAB list is empty...',

        ],
      ];
    }

    /**
     * BUILDING LIST
     */
    for ($i = 0; $i < count($arr_git); $i++) {
      $form[] = [
        '#prefix' => '<li class="groups_list__item">',
        $item['repo_del'] = [
          '#type' => 'submit',
          '#name' => $arr_git[$i]->id,
          '#value' => $arr_git[$i]->name,
          '#attributes' => ['class' => ['btn_link']],
        ],
        $item['repo_del'] = [
          '#type' => 'submit',
          '#name' => $arr_git[$i]->id,
          '#value' => 'Delete',
          '#attributes' => ['class' => ['btn_del']],
        ],

        '#suffix' => '</li>',
      ];
    }
    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $target_id = $form_state->getTriggeringElement()['#name'];
    $btn_value = $form_state->getTriggeringElement()['#value'];


    if ($btn_value == 'Delete') {
      if ($this->target == '/groups' && $this->target_id) {
        $form_state->set('target', '/projects');
        $form_state->set('target_id', $target_id);
        $form_state->set('action', 'DELETE');
      }
      else {
        $form_state->set('target', '/groups');
        $form_state->set('target_id', $target_id);
        $form_state->set('action', 'DELETE');

      }

    }//delete
    else {
      if ($this->target == NULL) {
        $form_state->set('parent', $target_id);
      }
      $form_state->set('target', '/groups');
      $form_state->set('target_id', $target_id);
      $form_state->set('action', 'GET');
      $form_state->set('child', '/projects');
    }// link


    /**
     * DATA FOR BTN 'BACK'
     */
    if ($btn_value == 'Back') {
      $form_state->set('target', '/groups');
      $form_state->set('target_id', NULL);
      $form_state->set('child', NULL);
      $form_state->set('action', 'GET');
    }


    $_SESSION['simple_gitlab_manager']['targets'] = [
      'target' => $form_state->get('target'),
      'target_id' => '/' . $form_state->get('target_id') . '' . $form_state->get('child'),
      'action' => $form_state->get('action'),
      'parent' => $form_state->get('parent'),
    ];


  } // submitForm


}
