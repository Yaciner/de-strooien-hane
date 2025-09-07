<?php

namespace Drupal\dms_layout\Plugin\Derivative;

use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\dms_layout\Entity\Layout;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LayoutBlockDeriver implements ContainerDeriverInterface {

  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static();
  }

  public function getDerivativeDefinition($derivative_id, $base_plugin_definition) {
    $derivatives = $this->getDerivativeDefinitions($base_plugin_definition);
    return $derivatives[$derivative_id] ?? NULL;
  }

  public function getDerivativeDefinitions($base_plugin_definition) {
    $layouts = Layout::loadMultiple();
    $derivatives = [];

    foreach ($layouts as $id => $layout) {
      $derivatives[$id] = [
        'admin_label' => $layout->label(),
      ] + $base_plugin_definition;
    }

    return $derivatives;
  }

}
