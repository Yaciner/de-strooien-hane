<?php

use Drupal\Core\Template\Attribute;
use Drupal\Component\Utility\Html;

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
  !empty($paragraph->field_media_alignment->value) ? $variables['attributes']['class'][] = 'pg--has-media-' . $paragraph->field_media_alignment->value : null;
  !empty($paragraph->field_vertical_alignment->value) ? $variables['attributes']['class'][] = 'pg-valign--' . $paragraph->field_vertical_alignment->value : null;

  $container_attributes = [
    'class' => [
      'paragraph__inner',
      'container'
    ]
  ];
  $variables['container_attributes'] = new Attribute($container_attributes);

  if ($background = $paragraph->field_background_color->value ?? NULL) {
    $variables['attributes']['class'][] = 'has-background';
    $variables['attributes']['class'][] = 'pg-bg--' . Html::getClass($background);
  }

  if (!empty($paragraph->field_vertical_alignment->value)) {
    $variables['attributes']['class'][] = 'pg-valign--' . Html::getClass($paragraph->field_vertical_alignment->value);
    unset($variables['content']['field_vertical_alignment']);
  }
}