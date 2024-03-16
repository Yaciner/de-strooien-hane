<?php

function mine_preprocess_region(&$variables) {
  $variables['attributes']['class'][] = 'region';
  $variables['attributes']['class'][] = 'region-' . str_replace('_', '-', $variables['region']);

  if($variables['region']) {
    if(!empty($variables['elements']['mine_views_block__general_site_settings_notification_bar'])) {
      $variables['attributes']['class'][] = 'has-notification';
    }
  }
}