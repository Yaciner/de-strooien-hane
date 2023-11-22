<?php

namespace Drupal\mine_paragraphs\Theme;

use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

class ThemeNegotiator implements ThemeNegotiatorInterface {

  /**
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  private $themeHandler;

  public function __construct(ThemeHandlerInterface $themeHandler) {
    $this->themeHandler = $themeHandler;
  }

  public function applies(RouteMatchInterface $route_match) {
    if ($route_match->getRouteName() === 'entity_browser.edit_form') {
      return TRUE;
    }
    return FALSE;
  }

  public function determineActiveTheme(RouteMatchInterface $route_match) {
    return $this->themeHandler->getDefault();
  }

}
