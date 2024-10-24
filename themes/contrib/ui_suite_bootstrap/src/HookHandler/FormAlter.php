<?php

declare(strict_types=1);

namespace Drupal\ui_suite_bootstrap\HookHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Alter forms.
 */
class FormAlter {

  /**
   * Alter forms.
   *
   * @param array $form
   *   The form structure.
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The form state.
   * @param string $form_id
   *   The form ID.
   */
  public function alter(array &$form, FormStateInterface $formState, string $form_id): void {
    // See \Drupal\Core\Form\FormBuilder::processForm().
    if ($formState->isMethodType('get') && $formState->getAlwaysProcess()) {
      $form['#after_build'][] = [
        static::class,
        'afterBuildAddGetFormMethod',
      ];
    }

    if ($this->isLayoutBuilderForm($form, $form_id)) {
      $form['#after_build'][] = [
        static::class,
        'afterBuildDisableDetailsAccordion',
      ];
    }
  }

  /**
   * Form element #after_build callback.
   */
  public static function afterBuildAddGetFormMethod(array $element, FormStateInterface $form_state): array {
    static::addGetFormMethod($element);
    return $element;
  }

  /**
   * Form element #after_build callback.
   */
  public static function afterBuildDisableDetailsAccordion(array $element, FormStateInterface $form_state): array {
    static::disableDetailsAccordion($element);
    return $element;
  }

  /**
   * Set form method to all form elements.
   *
   * To allow other processing to act based on this information.
   *
   * @param array $form
   *   The form or form element which children should have form method attached.
   */
  protected static function addGetFormMethod(array &$form): void {
    foreach (Element::children($form) as $child) {
      if (!isset($form[$child]['#form_method'])) {
        $form[$child]['#form_method'] = 'get';
      }
      static::addGetFormMethod($form[$child]);
    }
  }

  /**
   * Detect if Layout Builder form.
   *
   * @param array $form
   *   The form structure.
   * @param string $form_id
   *   The form ID.
   *
   * @return bool
   *   True for gin form.
   *
   * @see gin_lb_is_layout_builder_form_id()
   */
  protected function isLayoutBuilderForm(array &$form, string $form_id): bool {
    $form_ids = [
      'editor_image_dialog',
      'form-autocomplete',
      'layout_builder_add_block',
      'layout_builder_block_move',
      'layout_builder_configure_section',
      'layout_builder_remove_block',
      'layout_builder_update_block',
      'media_image_edit_form',
      'media_library_add_form_oembed',
      'media_library_add_form_upload',
      'section_library_add_section_to_library',
      'section_library_add_template_to_library',
    ];
    $form_id_contains = [
      'layout_builder_translate_form',
      'views_form_media_library_widget_',
    ];

    foreach ($form_id_contains as $form_id_contain) {
      if (\strpos($form_id, $form_id_contain) !== FALSE) {
        return TRUE;
      }
    }

    if (\in_array($form_id, $form_ids, TRUE)) {
      return TRUE;
    }

    if ($form_id === 'views_exposed_form' && isset($form['#id']) && $form['#id'] === 'views-exposed-form-media-library-widget') {
      return TRUE;
    }

    if (\strpos($form_id, 'layout_builder_form') !== FALSE) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Set bootstrap_accordion FALSE to all layout builder details form elements.
   *
   * @param array $form
   *   The form or form element which children should have form id attached.
   */
  protected static function disableDetailsAccordion(array &$form): void {
    foreach (Element::children($form) as $child) {
      if (isset($form[$child]['#type'])
        && $form[$child]['#type'] == 'details'
        && !isset($form[$child]['#bootstrap_accordion'])
      ) {
        $form[$child]['#bootstrap_accordion'] = FALSE;
      }
      static::disableDetailsAccordion($form[$child]);
    }
  }

}
