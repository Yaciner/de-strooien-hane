<?php
/**
 * @param $variables
 */
function mine_preprocess_paragraph(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['elements']['#paragraph'];

  // Remove clearfix class
  $variables['attributes']['class'] = array_diff($variables['attributes']['class'] ?? [], ['clearfix']);

  // Set default classes
  $variables['attributes']['class'][] = 'paragraph';
  $variables['attributes']['class'][] = 'paragraph--type--' . str_replace('_', '-', $paragraph->getType());
  $variables['attributes']['class'][] = 'paragraph--view-mode--' . $variables['elements']['#view_mode'];
}