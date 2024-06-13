<?php

use Drupal\node\Entity\Node;

function mine_preprocess_html(&$variables) {
  $path = \Drupal::service('extension.list.theme')->getPath('mine');
  $themePath = '/' . $path;

  // Front
  $variables['attributes']['class'][] = \Drupal::service('path.matcher')
    ->isFrontPage() ? 'front' : 'not-front';

  // Language
  $variables['attributes']['class'][] = 'lang-' . \Drupal::languageManager()->getCurrentLanguage()->getId();

  // Logged in/out
  $variables['attributes']['class'][] = $variables['logged_in'] ? 'logged-in' : 'logged-out';

  $path = \Drupal::service('path.current')->getPath();
  $pathArgs = explode('/',$path);

  // Path
  if(!empty($pathArgs[1])) {
    $variables['attributes']['class'][] = 'path-' . $pathArgs[1];
  }

  // Node
  // Node: type
  $node = \Drupal::routeMatch()->getParameter('node');
  $variables['attributes']['class'][] = isset($variables['node_type']) ? 'node-type-' . $variables['node_type'] : '';
  $variables['attributes']['class'][] = $node->field_paragraphs->target_id ?? NULL ? 'has-paragraphs' : 'no-paragraphs';
  $variables['attributes']['class'][] = ($node->field_hero->entity->type->target_id ?? '') !== 'hero' ? 'no-hero' : 'has-hero';
  $variables['attributes']['class'][] = ($node->field_paragraphs->entity->type->target_id ?? '') !== 'quickmenu' ? NULL : 'has-quickmenu';
  $variables['attributes']['class'][] = ($node->field_center_title->value ?? '') === '1' ? 'centered-title' : NULL;

  $gss = Node::load(31);
  if(!empty($gss->field_notification->value)) {
    $variables['attributes']['class'][] = 'bar-enabled';
  }

  // Taxonomy
  if(!empty($pathArgs[1] && !empty($pathArgs[2])) && !empty($pathArgs[3]) && ($pathArgs[1] == 'taxonomy') && (is_numeric($pathArgs[3]))) {
    $variables['attributes']['class'][] = 'taxonomy-term';
    $variables['attributes']['class'][] = 'taxonomy-term-' . $pathArgs[3];
  }

  // // Breakpoint labels
  // $breakpoints = theme_get_setting('breakpoints');
  // if ($breakpoints == 1) {
  //   $variables['attributes']['class'][] = 'breakpoint-labels';
  // }

  // Mobile menu
  $mobilePosition = theme_get_setting('mm_position');
  if(!empty($mobilePosition)) {
    $variables['attributes']['class'][] = 'mm-' . $mobilePosition;
  }

  // Notification bar
  // views_block:general_site_settings-notification_bar

  // https://css-tricks.com/the-current-state-of-telephone-links/
  // <meta name="format-detection" content="telephone=no">
  $formatDetection = [
    '#tag' => 'meta',
    '#attributes' => [
      'name' => 'format-detection',
      'content' => 'telephone=no'
    ]
  ];
  $variables['page']['#attached']['html_head'][] = [$formatDetection, 'format-detection'];

  $favAppleTouch = [
    '#tag' => 'link',
    '#attributes' => [
      'rel' => 'apple-touch-icon',
      'sizes' => '180x180',
      'href' => $themePath . '/dist/img/fav/apple-touch-icon.png',
    ],
  ];
  $fav32 = [
    '#tag' => 'link',
    '#attributes' => [
      'rel' => 'icon',
      'type' => 'image/png',
      'sizes' => '32x32',
      'href' => $themePath . '/dist/img/fav/favicon-32x32.png',
    ],
  ];
  $fav16 = [
    '#tag' => 'link',
    '#attributes' => [
      'rel' => 'icon',
      'type' => 'image/png',
      'sizes' => '16x16',
      'href' => $themePath . '/dist/img/fav/favicon-16x16.png',
    ],
  ];
  $favManifest = [
    '#tag' => 'link',
    '#attributes' => [
      'rel' => 'manifest',
      'href' => $themePath . '/dist/img/fav/site.webmanifest',
    ],
  ];
  $favSafariTab = [
    '#tag' => 'link',
    '#attributes' => [
      'rel' => 'mask-icon',
      'color' => '#666666',
      'href' => $themePath . '/dist/img/fav/safari-pinned-tab.svg',
    ],
  ];
  $favShortcut = [
    '#tag' => 'link',
    '#attributes' => [
      'rel' => 'shortcut icon',
      'href' => $themePath . '/dist/img/fav/favicon.ico',
    ],
  ];
  $favMicrosoftApp = [
    '#tag' => 'meta',
    '#attributes' => [
      'name' => 'msapplication-config',
      'content' => $themePath . '/dist/img/fav/browserconfig.xml',
    ],
  ];
  $favThemeColor = [
    '#tag' => 'meta',
    '#attributes' => [
      'name' => 'theme-color',
      'content' => '#ffffff',
    ],
  ];

  // Remove the favicon set by core if still present
  if (isset($variables['page']['#attached']['html_head_link'])) {
    foreach ($variables['page']['#attached']['html_head_link'] as $key => $headlinks) {
      foreach ($headlinks as $index => $headlink) {
        if (isset($headlink['rel']) && $headlink['rel'] == 'shortcut icon') {
          unset($variables['page']['#attached']['html_head_link'][$key][$index]);
          if (empty($variables['page']['#attached']['html_head_link'][$key])) {
            unset($variables['page']['#attached']['html_head_link'][$key]);
          }
        }
      }
    }
  }
  $variables['page']['#attached']['html_head'][] = [$favAppleTouch, 'apple-touch-icon'];
  $variables['page']['#attached']['html_head'][] = [$fav32, 'favicon-32px'];
  $variables['page']['#attached']['html_head'][] = [$fav16, 'favicon-16px'];
  $variables['page']['#attached']['html_head'][] = [$favManifest, 'favicon-manifest'];
  $variables['page']['#attached']['html_head'][] = [$favSafariTab, 'favicon-safari-tab'];
  $variables['page']['#attached']['html_head'][] = [$favShortcut, 'favicon-shortcut'];
  $variables['page']['#attached']['html_head'][] = [$favMicrosoftApp, 'msapplication-config'];
  $variables['page']['#attached']['html_head'][] = [$favThemeColor, 'theme-color'];
}
