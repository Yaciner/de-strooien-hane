<?php

namespace Drupal\mine_dev\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 *
 * @Block(
 *   id = "mine_dev_back_to_top",
 *   admin_label = "Mine DEV: Back to top"
 * )
 *
 */
class BackToTop extends BlockBase {
  public function build() {
    $build = [];

    $build[] = [
      '#markup' => '<div class="back-to-top">' . t('Back to top') . '</div>',
    ];

    return $build;
  }
}
