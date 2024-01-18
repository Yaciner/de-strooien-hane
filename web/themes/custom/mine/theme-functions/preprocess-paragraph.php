<?php

use Drupal\Core\Template\Attribute;
use Drupal\Component\Utility\Html;
use \Drupal\block\Entity\Block;
use Drupal\Core\Url;


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
  !empty($paragraph->field_grid->value) ? $variables['attributes']['class'][] = 'pg-grid--' . $paragraph->field_grid->value : null;

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
  else {
    if ($paragraph->getType() != 'hero') {
      $variables['attributes']['class'][] = 'pg-bg--none';
    }
  }

  if (!empty($paragraph->field_vertical_alignment->value)) {
    $variables['attributes']['class'][] = 'pg-valign--' . Html::getClass($paragraph->field_vertical_alignment->value);
    unset($variables['content']['field_vertical_alignment']);
  }
}

/**
 * Preprocessing the Text paragraph bundle to determine classes for column
 * layout.
 *
 * @param $variables
 */
function mine_preprocess_paragraph__text(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['elements']['#paragraph'];
  $variables['attributes']['class'][] = 'pg-textcol--' . count($paragraph->field_text_columns);
}


function mine_preprocess_paragraph__block(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['elements']['#paragraph'];
  $block = $variables['content']['field_block_to_embed']['#items'][0]->entity ?? null;
  if ($block instanceof \Drupal\block\Entity\Block) {
    $variables['attributes']['class'][] = $block->id();
  }
}

function mine_preprocess_paragraph__list_item(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['elements']['#paragraph'];
  if ($url = $variables['content']['field_list_item_link'][0]['#url'] ?? NULL) {
    $link = $paragraph->get('field_list_item_link')->first()->getValue();
    $variables['url'] = Url::fromUri($link['uri']);

    if ($url->isExternal()) {
      $variables['content']['field_list_item_link'][0]['#attributes']['target'] = '_blank';
    }
  }
}
