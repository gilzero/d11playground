<?php

declare(strict_types=1);

namespace Drupal\ui_suite_bootstrap\HookHandler;

use Drupal\Core\Template\Attribute;
use Drupal\ui_suite_bootstrap\Utility\Variables;

/**
 * Pre-processes variables for the "fieldset" theme hook.
 */
class PreprocessFieldset extends PreprocessFormElement {

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

    /** @var array $wrapper_attributes */
    $wrapper_attributes = $this->element->getProperty('wrapper_attributes', []);
    $wrapper_attributes = new Attribute($wrapper_attributes);

    /** @var array $label_attributes */
    $label_attributes = $this->element->getProperty('label_attributes', []);
    $label_attributes = new Attribute($label_attributes);

    /** @var array $inner_wrapper_attributes */
    $inner_wrapper_attributes = $this->element->getProperty('inner_wrapper_attributes', []);
    $inner_wrapper_attributes = new Attribute($inner_wrapper_attributes);

    // Layout: horizontal form.
    // _title_display is created by Webform.
    if ($this->element->getProperty('title_display') == 'inline' || $this->element->getProperty('_title_display') == 'inline') {
      $wrapper_attributes->addClass([
        'row',
        'mb-3',
      ]);
      $label_attributes->addClass('col-form-label');
    }
    // Display fieldset as card.
    else {
      $wrapper_attributes->addClass([
        'card',
        'mb-3',
      ]);
      $label_attributes->addClass('card-header');
      $inner_wrapper_attributes->addClass('card-body');
    }

    // Merge wrapper attributes.
    $this->variables->setAttributes($wrapper_attributes->toArray());
    $variables['legend']['attributes']->merge($label_attributes);
    // Cannot use map directly because of the attributes' management.
    $this->variables->offsetSet('inner_wrapper_attributes', $inner_wrapper_attributes);

    $this->validation();
  }

}
