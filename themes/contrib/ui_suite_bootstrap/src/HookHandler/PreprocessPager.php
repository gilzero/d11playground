<?php

declare(strict_types=1);

namespace Drupal\ui_suite_bootstrap\HookHandler;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Template\Attribute;
use Drupal\ui_patterns_settings\Plugin\UiPatterns\SettingType\LinksSettingType;

/**
 * Ensure pager structure fits into links prop structure.
 */
class PreprocessPager {

  use StringTranslationTrait;

  /**
   * Ensure pager structure fits into link prop structure.
   *
   * @param array $variables
   *   The preprocessed variables.
   */
  public function preprocess(array &$variables): void {
    if (!isset($variables['items'])) {
      return;
    }
    $this->setLinksAriaLabel($variables['items']);

    $before = LinksSettingType::normalize(\array_filter([
      $variables['items']['first'] ?? [],
      $variables['items']['previous'] ?? [],
    ]));

    $pages = LinksSettingType::normalize($variables['items']['pages'] ?? []);
    if (isset($variables['current'])) {
      foreach ($pages as $key => $page) {
        if ($page['title'] == $variables['current']) {
          unset($pages[$key]['url']);
        }
      }
    }

    $after = LinksSettingType::normalize(\array_filter([
      $variables['items']['next'] ?? [],
      $variables['items']['last'] ?? [],
    ]));

    $variables['preprocessed_items'] = \array_merge($before, $pages, $after);
  }

  /**
   * Set aria-label attribute.
   *
   * @param array $items
   *   The items to check for.
   */
  protected function setLinksAriaLabel(array &$items): void {
    foreach ($this->getLinksAriaLabel() as $link_key => $aria_label) {
      if (isset($items[$link_key]['attributes']) && $items[$link_key]['attributes'] instanceof Attribute) {
        $items[$link_key]['attributes']->setAttribute('aria-label', $aria_label);
      }
    }
  }

  /**
   * Get special links aria label.
   *
   * @return array
   *   The list of special links aria label.
   */
  protected function getLinksAriaLabel(): array {
    return [
      'first' => $this->t('First'),
      'previous' => $this->t('Previous'),
      'next' => $this->t('Next'),
      'last' => $this->t('Last'),
    ];
  }

}
