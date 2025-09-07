<?php

namespace Drupal\dms_layout\Entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

class LayoutListBuilder extends EntityListBuilder {

  public function buildHeader() {
    return ['label' => $this->t('Label')] + parent::buildHeader();
  }

  public function buildRow(EntityInterface $entity) {
    return ['label' => $entity->label()] + parent::buildRow($entity);
  }

}
