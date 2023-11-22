<?php

namespace Drupal\mine_paragraphs\Plugin\EntityReferenceSelection;
use Drupal\Core\Entity\Annotation\EntityReferenceSelection;
use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;
use Drupal\Component\Utility\Html;

/**
 * Filters the block listing for an entity reference field.
 *
 * @EntityReferenceSelection(
 *   id = "blocks",
 *   label = @Translation("Filter by blocks placed in paragraph_blocks region"),
 *   entity_types = {"block"},
 *   group = "blocks",
 *   weight = 1
 * )
 */
class ClientAllowedBlocks extends DefaultSelection {

  /**
   * {@inheritdoc}
   */
  public function getReferenceableEntities($match = NULL, $match_operator = 'CONTAINS', $limit = 0) {
    $target_type = $this->configuration['target_type'];

    $query = $this->buildEntityQuery($match, $match_operator);
    if ($limit > 0) {
      $query->range(0, $limit);
    }

    $result = $query->execute();

    if (empty($result)) {
      return array();
    }

    $options = array();
    $entities = $this->entityTypeManager->getStorage($target_type)->loadMultiple($result);
    foreach ($entities as $entity_id => $entity) {
      /** @var $entity \Drupal\block\Entity\Block */
      $block_theme = $entity->getTheme();
      $block_region = $entity->getRegion();

      // Only add blocks which are in a non-display region that we use to make a
      // selection for blocks the customer admin is allowed to pick as a reference.

      $default_theme = \Drupal::config('system.theme')->get('default');

      if ($block_theme == $default_theme && $block_region == 'paragraph_blocks') {
        $bundle = $entity->bundle();
        $options[$bundle][$entity_id] = Html::escape($this->entityRepository->getTranslationFromContext($entity)->label());
      }
    }

    // Sort the options alphabetically for easier UI selection.
    asort($options['block']);
    return $options;
  }
}
