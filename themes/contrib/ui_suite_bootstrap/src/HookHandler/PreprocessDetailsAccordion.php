<?php

declare(strict_types=1);

namespace Drupal\ui_suite_bootstrap\HookHandler;

use Drupal\ui_suite_bootstrap\Utility\Variables;

/**
 * Pre-processes variables for the "details__accordion" theme hook.
 */
class PreprocessDetailsAccordion extends PreprocessFormElement {

  /**
   * Preprocess form element.
   *
   * @param array $variables
   *   The variables to preprocess.
   */
  public function preprocess(array &$variables): void {
    if (!isset($variables['element'])) {
      return;
    }

    $this->variables = Variables::create($variables);
    $this->element = $this->variables->element;
    if (!$this->element) {
      return;
    }

    // Remove Core library for details HTML tag.
    /** @var array $attached */
    $attached = $this->element->getProperty('attached', []);
    if (isset($attached['library'])) {
      $key = \array_search('core/drupal.collapse', $attached['library'], TRUE);
      if ($key !== FALSE) {
        unset($attached['library'][$key]);
      }
    }
    $this->element->setProperty('attached', $attached);

    $this->validation();
  }

}
