<?php

namespace Drupal\simple_gitlab_manager;

use Drupal\simple_gitlab_manager\Form\GroupsForm;

/**
 * ServiceGit service.
 */
class ServiceGit {


  // Simple access parameters to GitLab API
  const URL_COMMON = 'https://gitlab.com/api/v4';

  const TOKEN = 'bnu8kG7Km9PadPa_FFak';

  // Arr Groups & Projects
  public $arr;
  // *END* Arr Groups & Projects

  /**
   * Method description.
   *
   * @param $target
   * @param $target_id
   * @param $action
   */

  public function __construct($target,$target_id,$action) {
//    $this->target = $target;
//    $this->target_id = $target_id;
//    $this->action = $action;
    return $this->arr = $this->get_array_git($target, $target_id, $action);
  }

  private function get_array_git($target, $target_id, $action) {

    if ($target == NULL&&$action == NULL) {
      $target = '/groups';
      $action = 'GET';

    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, self::URL_COMMON . $target . $target_id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $action);


    $headers = [];
    $headers[] = 'Private-Token:' . self::TOKEN;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch));

    if (curl_errno($ch)) {
      print 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    return $result;
  }



}
