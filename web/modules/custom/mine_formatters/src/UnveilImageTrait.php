<?php

namespace Drupal\mine_formatters;

use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\Render\Markup;
use Drupal\Core\Url;
use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\ImageStyleInterface;
use Drupal\media\Entity\MediaType;
use Drupal\media\MediaInterface;

trait UnveilImageTrait {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    return $field_definition->getFieldStorageDefinition()->getSetting('target_type') == 'media';
  }

  /**
   * {@inheritdoc}
   *
   * This has to be overridden because FileFormatterBase expects $item to be
   * of type \Drupal\file\Plugin\Field\FieldType\FileItem and calls
   * isDisplayed() which is not in FieldItemInterface.
   */
  protected function needsEntityLoad(EntityReferenceItem $item) {
    return !$item->hasNewEntity();
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $media_items = EntityReferenceFormatterBase::getEntitiesToView($items, $langcode);
    $files = $this->getEntitiesToView($items, $langcode);

    foreach ($elements as $delta => $element) {
      if ($this->getSetting('image_link') === 'file' || $this->isLightBox($items->getEntity())) {
        $elements[$delta]['#url'] = $this->getLightBoxUrl($files[$delta], $items->getEntity()->id());
      }

      // If media is a video, make #url a link to the video with lightbox.
      if ($this->isVideo($media_items[$delta])) {
        $video_id = $media_items[$delta]->field_video_id->value ?? NULL;
        $service = $media_items[$delta]->field_video_service->value ?? NULL;
        $behavior = $media_items[$delta]->field_video_behavior->value ?? NULL;

        if ($behavior === NULL || $behavior === 'lightbox') {
          $elements[$delta]['#url'] = $this->getVideoUrl($media_items[$delta]);

          if ($service === 'wistia') {
//            unset($elements[$delta]['#url']);
//            $elements[$delta]['#prefix'] = Markup::create('<a data-fancybox data-src="#wistia-id-'.$video_id.'" href="javascript:;" class="lightbox video">');
//            $template = '</a><div class="wistia--lightbox"><div id="wistia-id-{{ video_id }}" style="width: 60%;">';
//            $template .= '</div></div>';
//            $tmp = [
//              '#type' => 'inline_template',
//              '#template' => $template,
//              '#context' => [
//                'video_id' => $video_id,
//              ],
//            ];
//            $elements[$delta]['#suffix'] = \Drupal::service('renderer')->render($tmp);
            $elements[$delta]['#attached']['library'][] = 'mine_formatters/wistia_video_iframe_api';
          }
        }
        else {
          if ($service === 'youtube') {
            $src = "https://www.youtube.com/embed/$video_id?" . http_build_query([
                'autoplay' => $behavior === 'autoplay' ? 1 : 0,
                'loop' => $behavior === 'autoplay' ? 1 : 0,
                'modest_branding' => 1,
                'disablekb' => 1,
              ]);

            $elements[$delta] = [
              '#type' => 'inline_template',
              '#template' => '<div class="iframe-wrapper"><iframe style="width: 100%; max-width: 100%" allow="autoplay" frameborder=0 src={{ src }}></iframe></div>',
              '#context' => [
                'src' => $src,
              ],
            ];
          }
          elseif ($service === 'vimeo') {
            $elements[$delta] = [
              '#theme' => 'file_video',
              '#attributes' => [
                'class' => [
                  'not-loaded',
                  'video-' . $video_id,
                ],
                'data-service' => $service,
                'data-vid' => $video_id,
              ],
              '#attached' => [
                'library' => [
                  'mine_formatters/video-embed--vimeo',
                ],
              ],
            ];

            if ($behavior === 'autoplay') {
              $elements[$delta]['#attributes']['autoplay'] = 'autoplay';
              $elements[$delta]['#attributes']['playsinline'] = 'playsinline';
              $elements[$delta]['#attributes']['muted'] = 'muted';
              $elements[$delta]['#attributes']['loop'] = 'loop';
              $elements[$delta]['#attributes']['poster'] = $this->getFileUrl($files[$delta]);
            }
            else {
              $elements[$delta]['#attributes']['controls'] = 'controls';
              $elements[$delta]['#attributes']['poster'] = $this->getFileUrl($files[$delta]);
            }
          }
          elseif ($service === 'wistia') {
            $template = '';
            // Autoplay
            if ($behavior === 'autoplay') {
              $template .= '<div class="video-container video-container--autoplay"><div class="wistia_video wistia_embed wistia_async_{{ video_id }} autoPlay=true controlsVisibleOnLoad=false muted=true endVideoBehavior=loop" style="height:100%;position:absolute;width:100%;"></div></div>';
            }
            // Inline
            else {
              $template .= '<div class="video-container video-container--inline"><div class="wistia_embed wistia_async_{{ video_id }} videoFoam=true" style="height:100%;position:relative;width:100%;"></div></div>';
            }
            $elements[$delta] = [
              '#type' => 'inline_template',
              '#template' => $template,
              '#context' => [
                'video_id' => $video_id,
              ],
            ];
            $elements[$delta]['#attached']['library'][] = 'mine_formatters/wistia_video_player_api';
          }
        }
      }

      // Fancybox gallery
      if ($this->isLightBox($items->getEntity()) && isset($elements[$delta]['#url'])) {
        $url_options = $elements[$delta]['#url']->getOptions();
        $url_options['attributes']['data-fancybox'] = 'gallery-' . $items->getEntity()->id();
        $elements[$delta]['#url']->setOptions($url_options);
      }
    }

    return $elements;
  }

  protected function getEntitiesToView(EntityReferenceFieldItemListInterface $items, $langcode) {
    $media_items = EntityReferenceFormatterBase::getEntitiesToView($items, $langcode);

    foreach ($media_items as $delta => $media) {
      assert($media instanceof MediaInterface);
      $field = $media->getSource()
        ->getSourceFieldDefinition(MediaType::load($media->bundle()))
        ->getName();
      $file = $media->{$field}->entity;
      $file->_referringItem = $media->{$field}[0];
      $files[] = $media->{$field}->entity;
    }

    return $files ?? [];
  }

  protected function isVideo(MediaInterface $media) {
    return !empty($media->field_video_service->value) && !empty($media->field_video_id->value);
  }

  protected function getVideoUrl(MediaInterface $media) {
    $service = $media->field_video_service->value ?? NULL;
    $video_id = $media->field_video_id->value ?? NULL;

    $url = Url::fromRoute('<none>');

    if ($service === 'youtube') {
      $url = Url::fromUri('https://www.youtube.com/embed/' . $video_id);
      $url->setOption('attributes', [
        'class' => [
          'lightbox',
          'video',
        ],
      ]);
    }
    elseif ($service === 'vimeo') {
      $url = Url::fromUri("https://vimeo.com/" . $video_id);
      $url->setOption('attributes', [
        'class' => [
          'lightbox',
          'video',
        ],
      ]);
    }
    elseif ($service === 'wistia') {
      $url = Url::fromUri('https://fast.wistia.net/embed/iframe/'.$video_id.'?autoPlay=true');
      $url->setOptions([
        'attributes' => [
          'data-type' => "iframe",
          'class' => [
            'lightbox',
            'video',
          ],
        ],
      ]);
    }

    return $url;
  }

  protected function getLightBoxUrl(FileInterface $file, $fancybox) {
    $uri = $this->getFileUrl($file);

    return Url::fromUri($uri, [
      'attributes' => [
        'class' => [
          'lightbox',
          'style',
        ],
        'data-fancybox' => $fancybox,
      ],
    ]);
  }

  protected function isLightBox(FieldableEntityInterface $entity) {
    return (bool) ($entity->field_lightbox->value ?? FALSE);
  }

  protected function getFileUrl($file) {
    if ($style = $this->getSetting('image_link_image_style')) {
      $style = ImageStyle::load($style);
      return $style->buildUrl($file->getFileUri());
    }

    return \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
  }

}
