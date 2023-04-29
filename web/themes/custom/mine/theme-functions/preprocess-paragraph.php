<?php
/**
 * @param $variables
 */
function mine_preprocess_paragraph(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['elements']['#paragraph'];
}