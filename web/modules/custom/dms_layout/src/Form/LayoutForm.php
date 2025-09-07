<?php

namespace Drupal\dms_layout\Form;

use Drupal\block\Entity\Block;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;

class LayoutForm extends EntityForm {

  private const FORM_ID = 'layout-form';

  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\dms_layout\Entity\Layout $entity */
    $entity = $this->entity;

    $form['#id'] = self::FORM_ID;
    $form['#tree'] = TRUE;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => $entity->label(),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $entity->id(),
      '#maxlength' => EntityTypeInterface::ID_MAX_LENGTH,
      '#machine_name' => [
        'exists' => [$this, 'exists'],
        'source' => ['label'],
      ],
      '#disabled' => !$entity->isNew(),
      '#required' => TRUE,
    ];

    /** @var \Drupal\Core\Layout\LayoutPluginManager $layout_manager */
    $layout_manager = \Drupal::service('plugin.manager.core.layout');
    $layouts = $layout_manager->getLayoutOptions();

    $form['layout'] = [
      '#type' => 'select',
      '#title' => $this->t('Layout'),
      '#options' => $layouts,
      '#default_value' => $entity->get('layout'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => [get_class($this), 'ajaxUpdate'],
        'wrapper' => self::FORM_ID,
      ],
    ];

    $layout = $entity->get('layout');

    if ($form_state->getValue('layout')) {
      $layout = $form_state->getValue('layout');
    }

    /** @var \Drupal\Core\Layout\LayoutDefault $plugin */
    if ($layout && ($plugin = $layout_manager->createInstance($layout, $entity->get('plugin') ?? []))) {
      $form['plugin'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Plugin'),
      ];


      $form['plugin'] += $plugin->buildConfigurationForm([], new FormState());
    }

    /** @var \Drupal\Core\Layout\LayoutDefinition $definition */
    if ($layout && ($definition = $layout_manager->getDefinition($layout))) {
      $form['regions'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Regions'),
      ];

      foreach ($definition->getRegions() as $key => $region) {
        $form['regions'][$key] = [
          '#type' => 'entity_autocomplete',
          '#maxlength' => PHP_INT_MAX,
          '#target_type' => 'block',
          '#tags' => TRUE,
          '#default_value' => Block::loadMultiple($entity->get('regions')[$key] ?? []),
          '#selection_handler' => 'default',
          '#selection_settings' => [],
          '#title' => $region['label'],
        ];
      }
    }

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->entity->set('plugin', $form_state->getValue('plugin'));
    $this->entity->set('regions', array_map(fn ($region) => array_column($region ?? [], 'target_id'), $form_state->getValue('regions')));

    $this->messenger()->addStatus($this->t('The configuration options have been saved.'));
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    
    \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
  }

  public function exists($id) {
    return !empty($this->entityTypeManager->getStorage($this->entity->getEntityTypeId())->load($id));
  }

  public static function ajaxUpdate(array $form, FormStateInterface $form_state) {
    return $form;
  }

}
