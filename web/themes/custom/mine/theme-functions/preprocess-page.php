<?php

function mine_preprocess_page(&$variables) {
  $current_user = \Drupal::currentUser();
  if ($variables['is_front'] && !$current_user->isAuthenticated()) {
    $variables['#attached']['library'][] = 'mine/preloader';
  }
}