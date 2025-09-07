<?php

namespace Drupal\dms_layout\Plugin\Block;

use Drupal\block\Entity\Block;
use Drupal\Component\Utility\Html;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\dms_layout\Entity\Layout;

/**
 * @Block(
 *   id = "layout",
 *   admin_label = @Translation("Layout"),
 *   deriver = "Drupal\dms_layout\Plugin\Derivative\LayoutBlockDeriver",
 * )
 */
class LayoutBlock extends BlockBase {

  public function build() {
    $layout = $this->getDerivativeId();
    $layout = Layout::load($layout);

    /** @var \Drupal\Core\Layout\LayoutPluginManager $layout_plugin_manager */
    $layout_plugin_manager = \Drupal::service('plugin.manager.core.layout');
    $block_view_builder = \Drupal::entityTypeManager()->getViewBuilder('block');

    /** @var \Drupal\Core\Layout\LayoutDefault $plugin */
    $plugin = $layout_plugin_manager->createInstance($layout->get('layout'), $layout->get('plugin') ?? []);

    $regions = [];
    foreach ($layout->get('regions') ?? [] as $region => $blocks) {
      foreach ($blocks as $delta => $block) {
        $build = [];

        if ($block = Block::load($block)) {
          $access = $block->access('view', NULL, TRUE);


          if ($access->isAllowed()) {
            $build = $block_view_builder->view($block);
          }

          CacheableMetadata::createFromRenderArray($build)
            ->addCacheableDependency($access)
            ->addCacheableDependency($block)
            ->applyTo($build);
            
          $regions[$region][$block->id()] = $build;
          $regions[$region][$block->id()]['#weight'] = $delta;
        }
      }
    }

    $build = $plugin->build($regions);

    $build['#theme'] .= '__' . $this->getDerivativeId();
    $build['#attached'] = [];
    $build['#attributes']['class'][] = 'layout';
    $build['#attributes']['class'][] = 'layout--' . Html::getClass($this->getDerivativeId());

    return [$build];
  }

}
