<?php

/**
 * Implements hook_theme_suggestions_HOOK_alter() for page templates.
 * @param array $suggestions
 * @param array $variables
 */
function mine_theme_suggestions_page_alter(array &$suggestions, array $variables) {
  $node = \Drupal::routeMatch()->getParameter('node');
  if (isset($node) && $node instanceof \Drupal\node\NodeInterface) {
    $suggestions[] = 'page__node__' . $node->bundle();
  }
}

/**
 * Implements hook_theme_suggestions_hook_alter() to add field template suggestions.
 * @param array $suggestions
 * @param array $variables
 */
function mine_theme_suggestions_field_alter(array &$suggestions, array $variables) {
  $field = $variables['element'];

  switch ($field['#field_type']) {
    case 'entity_reference_revisions':
      /** @var \Drupal\field\Entity\FieldConfig $fc */
      $fc = \Drupal\field\Entity\FieldConfig::loadByName($field['#entity_type'], $field['#bundle'], $field['#field_name']);
      // If we are dealing with Paragraphs as the ERR handler, add custom field template suggestion,
      // so that all Paragraphs fields output without extra wrappers, regardless of field name.
      if ($fc->getSetting('target_type') == 'paragraph') {
        $field_type_suggestion = array_shift($suggestions);
        array_unshift($suggestions, $field_type_suggestion, $field_type_suggestion . '__paragraph');
      }
      break;
  }
}
