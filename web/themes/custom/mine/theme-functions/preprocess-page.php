<?php

function mine_preprocess_page(&$variables) {
  $current_user = \Drupal::currentUser();
  if ($variables['is_front'] && !$current_user->isAuthenticated()) {
    $variables['#attached']['library'][] = 'mine/preloader';
  }
  $host = \Drupal::request()->getSchemeAndHttpHost();
  $variables['#attached']['drupalSettings']['theme_path'] = $host . "/" . \Drupal::service('extension.list.theme')->getPath('mine');
}