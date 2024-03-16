<?php

function mine_preprocess_block(&$variables) {

}

function mine_preprocess_block__page_title_block(&$variables) {
  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof \Drupal\node\NodeInterface && ($node->field_hero->entity->type->target_id ?? '') === 'hero') {
    unset($variables['content']);
  }
}

/**
 * Implements hook_preprocess_HOOK() for system_branding_block block.
 */
function mine_preprocess_block__system_branding_block(&$variables) {
  $node = \Drupal::routeMatch()->getParameter('node');
  $current_user = \Drupal::currentUser();

  if(($node->field_hero->entity->type->target_id ?? '') === 'hero' && !$current_user->isAuthenticated()) {
    $variables['site_logo'] = '/themes/custom/mine/logo--neg.svg';
  }
}
