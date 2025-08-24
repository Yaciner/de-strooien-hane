<?php

use Drupal\block\Entity\Block;

function mine_preprocess_block(&$variables) {
}

function mine_preprocess_block__page_title_block(&$variables) {
  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof \Drupal\node\NodeInterface && ($node->field_hero->entity->type->target_id ?? '') === 'hero') {
    unset($variables['content']);
  }
}

function mine_preprocess_block__system_branding_block(&$variables) {
  // Get region of this block
  $block = Block::load($variables['elements']['#id']);
  $region = $block->getRegion();
  if ($region == 'header') $variables['site_logo'] = '/themes/custom/mine/logo--neg.svg';
}