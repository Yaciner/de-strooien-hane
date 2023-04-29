<?php

namespace Drupal\mine_formatters\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\mine_formatters\UnveilImageTrait;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;

/**
 * @FieldFormatter(
 *   id = "unveil_image",
 *   label = @Translation("Unveil image"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class UnveilImageFormatter extends ImageFormatter {

  use UnveilImageTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
        'image_link_image_style' => '',
        'image_only' => FALSE,
      ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);

    $image_styles = ImageStyle::loadMultiple();
    foreach ($image_styles as $key => $image_style) {
      $image_link_image_styles[$image_style->id()] = $image_style->label();
    }

    $element['image_link_image_style'] = [
      '#title' => $this->t('Image style of the linked file itself'),
      '#type' => 'select',
      '#options' => $image_link_image_styles,
      '#default_value' => (!empty($this->getSetting('image_link_image_style'))) ? $this->getSetting('image_link_image_style') : "",
      '#empty_option' => t("None (original image)"),
    ];

    $element['image_only'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Image only'),
      '#description' => $this->t('Render only the &ltimg&gt tag.'),
      '#default_value' => $this->getSetting('image_only'),
    ];

    return $element;
  }

  public function view(FieldItemListInterface $items, $langcode = NULL) {
    $elements = parent::view($items, $langcode);

    if ($this->getSetting('image_only')) {
      return $elements[0] ?? [];
    }

    return $elements;
  }

}
