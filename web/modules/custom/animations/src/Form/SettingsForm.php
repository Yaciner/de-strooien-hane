<?php

namespace Drupal\animations\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Entity\ParagraphsType;

class SettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['animations.settings'];
  }

  public function getFormId() {
    return 'animations_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('animations.settings');

    $form['#tree'] = TRUE;

    $form['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $config->get('enabled'),
    ];

    $form['aos'] = [
      '#type' => 'container',
    ];

    $form['aos']['aos'] = [
      '#type' => 'textfield',
      '#title' => $this->t('AOS animation'),
      '#default_value' => $config->get('aos'),
    ];

    $form['aos']['duration'] = [
      '#type' => 'number',
      '#title' => $this->t('AOS duration'),
      '#default_value' => $config->get('aos.duration'),
    ];

    $form['paragraphs'] = [
      '#type' => 'details',
      '#title' => $this->t('Paragraphs'),
      '#open' => $config->get('enabled'),
    ];

    $types = ParagraphsType::loadMultiple();

    foreach ($types as $type) {
      $form['paragraphs'][$type->id()]['enabled'] = [
        '#type' => 'checkbox',
        '#title' => $type->label(),
        '#default_value' => $config->get('paragraphs.' . $type->id() . '.enabled'),
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('animations.settings')
      ->set('enabled', $form_state->getValue('enabled'))
      ->set('aos', $form_state->getValue('aos'))
      ->set('paragraphs', $form_state->getValue('paragraphs'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
