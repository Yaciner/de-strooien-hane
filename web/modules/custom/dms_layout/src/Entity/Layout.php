<?php

namespace Drupal\dms_layout\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * @ConfigEntityType(
 *   id = "layout",
 *   label = @Translation("Layout"),
 *   label_collection = @Translation("Layouts"),
 *   handlers = {
 *     "storage" = "\Drupal\Core\Config\Entity\ConfigEntityStorage",
 *     "list_builder" = "\Drupal\dms_layout\Entity\LayoutListBuilder",
 *     "access" = "\Drupal\entity\EntityAccessControlHandler",
 *     "permission_provider" = "Drupal\entity\EntityPermissionProvider",
 *     "route_provider" = {
 *       "html" = "\Drupal\entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "form" = {
 *       "default" = "\Drupal\dms_layout\Form\LayoutForm",
 *       "delete" = "\Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *   },
 *   admin_permission = "administer dms_layout",
 *   links = {
 *     "collection" = "/admin/dms/layouts",
 *     "add-form" = "/admin/dms/layouts/add",
 *     "edit-form" = "/admin/dms/layouts/{layout}/edit",
 *     "delete-form" = "/admin/dms/layouts/{layout}/delete",
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "layout",
 *     "regions",
 *     "plugin",
 *   },
 * )
 */
class Layout extends ConfigEntityBase {



}
