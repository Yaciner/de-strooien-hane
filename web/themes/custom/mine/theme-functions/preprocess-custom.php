<?php

function mine_preprocess_mimemail_message(&$variables) {
  $config = \Drupal::config('system.site');
  $variables['site_name'] = $config->get('name');
}
