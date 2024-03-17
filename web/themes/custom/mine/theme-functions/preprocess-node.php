<?php

function mine_preprocess_node(&$variables) {
  /** @var \Drupal\node\Entity\Node $node */
  $node = $variables['node'];
}

function mine_preprocess_node__brand_item__teaser(&$variables) {
  /** @var \Drupal\node\Entity\Node $node */
  $node = $variables['node'];

  if (!$node->get('field_brand_info')->isEmpty()) {
    $variables['attributes']['class'][] = 'use-ajax';
    $variables['attributes']['data-dialog-type'] = 'modal';
    $variables['attributes']['data-dialog-options'] = json_encode([
      "width" => "calc(100% - 20px)",
      "dialogClass" => "modal--node"
    ]);
  }
  else {
    $variables['url'] = FALSE;
  }

}