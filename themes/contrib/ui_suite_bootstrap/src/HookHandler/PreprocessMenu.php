<?php

declare(strict_types=1);

namespace Drupal\ui_suite_bootstrap\HookHandler;

use Drupal\ui_patterns_settings\Plugin\UiPatterns\SettingType\LinksSettingType;

/**
 * Ensure menu structure fits into links prop structure.
 */
class PreprocessMenu {

  /**
   * Ensure menu structure fits into links prop structure.
   *
   * @param array $variables
   *   The preprocessed variables.
   */
  public function preprocess(array &$variables): void {
    $variables['preprocessed_items'] = LinksSettingType::normalize($variables['items']);
  }

}
