<?php

function mine_preprocess_block(&$variables) {

}

function mine_preprocess_block__page_title_block(&$variables) {
  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof \Drupal\node\NodeInterface && ($node->field_hero->entity->type->target_id ?? '') === 'hero') {
    unset($variables['content']);
  }
}