<?php

declare(strict_types=1);

namespace Drupal\ui_suite_bootstrap\HookHandler;

use Drupal\ui_patterns_settings\Plugin\UiPatterns\SettingType\LinksSettingType;
use Drupal\ui_suite_bootstrap\Utility\Bootstrap;

/**
 * Ensure links structure fits into dropdown structure.
 */
class PreprocessLinksDropbutton {

  /**
   * Ensure links structure fits into dropdown structure.
   *
   * @param array $variables
   *   The preprocessed variables.
   */
  public function preprocess(array &$variables): void {
    if (empty($variables['links'])) {
      return;
    }

    $links = LinksSettingType::normalize(\array_filter(
      $variables['links'],
    ));
    $first_link = \array_shift($links);

    // Not exactly a variant detection method but it is ok for now.
    $button_variant = \str_replace('-', '_', Bootstrap::cssClassFromString($first_link['title'], 'outline_dark'));

    // Detect size from type.
    if (isset($variables['attributes']['dropbutton_type'])) {
      if (\str_contains($variables['attributes']['dropbutton_type'], 'small')) {
        $button_variant .= '__sm';
      }
      if (\str_contains($variables['attributes']['dropbutton_type'], 'large')) {
        $button_variant .= '__lg';
      }
      unset($variables['attributes']['dropbutton_type']);
    }

    $variables['dropdown'] = [
      '#type' => 'pattern',
      '#id' => 'dropdown',
      '#title' => $first_link['title'],
      '#button_url' => $first_link['url'] ?? '',
      '#button_attributes' => $first_link['attributes'],
      '#button_variant' => $button_variant,
      '#button_split' => !empty($links),
      '#content' => empty($links) ? [] : $links,
      '#attributes' => $variables['attributes'],
    ];
  }

}
