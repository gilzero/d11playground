<?php

declare(strict_types=1);

namespace Drupal\ui_suite_bootstrap\HookHandler;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\ui_suite_bootstrap\Utility\Variables;
use Drupal\views\ViewExecutable;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Add theme suggestions.
 */
class ThemeSuggestionsAlter implements ContainerInjectionInterface {

  /**
   * The Variables object.
   *
   * @var \Drupal\ui_suite_bootstrap\Utility\Variables
   */
  protected $variables;

  /**
   * An element object provided in the variables array, may not be set.
   *
   * @var \Drupal\ui_suite_bootstrap\Utility\Element|false
   */
  protected $element;

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected CurrentRouteMatch $currentRouteMatch;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected RequestStack $requestStack;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
   *   The current route match.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   */
  public function __construct(
    CurrentRouteMatch $currentRouteMatch,
    RequestStack $requestStack,
  ) {
    $this->currentRouteMatch = $currentRouteMatch;
    $this->requestStack = $requestStack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('current_route_match'),
      $container->get('request_stack')
    );
  }

  /**
   * Template suggestions for details.
   *
   * To avoid impacting forms in the off-canvas sidebar. Mainly for Layout
   * Builder.
   *
   * @param array $suggestions
   *   The list of suggestions.
   * @param array $variables
   *   The theme variables.
   */
  public function details(array &$suggestions, array $variables): void {
    $this->variables = Variables::create($variables);
    $this->element = $this->variables->element;

    if ($this->element
      && !$this->element->hasProperty('bootstrap_accordion')
      && $this->isLayoutBuilderRoute($variables)
    ) {
      $this->element->setProperty('bootstrap_accordion', FALSE);
    }

    if ($this->element && $this->element->getProperty('bootstrap_accordion', TRUE)) {
      $suggestions[] = 'details__accordion';
    }
  }

  /**
   * Template suggestions for input.
   *
   * @param array $suggestions
   *   The list of suggestions.
   * @param array $variables
   *   The theme variables.
   */
  public function input(array &$suggestions, array $variables): void {
    $this->variables = Variables::create($variables);
    $this->element = $this->variables->element;

    if ($this->element && $this->element->isButton()) {
      $hook = 'input__button';
      $suggestions[] = $hook;
    }
  }

  /**
   * Detect Layout Builder route.
   *
   * @param array $variables
   *   The theme variables.
   *
   * @return bool
   *   True if Layout Builder route. False otherwise.
   *
   * @see gin_lb_theme_suggestions_alter()
   */
  protected function isLayoutBuilderRoute(array $variables): bool {
    $route_name = $this->currentRouteMatch->getRouteName();
    if (\in_array($route_name, [
      'editor.image_dialog',
      'editor.link_dialog',
      'editor.media_dialog',
      'layout_builder.add_block',
      'layout_builder.choose_block',
      'layout_builder.choose_inline_block',
      'layout_builder.choose_section',
      'layout_builder.remove_block',
      'layout_builder.remove_section',
      'media_library.ui',
      'section_library.add_section_to_library',
      'section_library.add_template_to_library',
      'view.media_library.widget',
      'view.media_library.widget_table',
    ], TRUE)) {
      return TRUE;
    }

    // For ajax the route is views.ajax
    // So a look to the suggestions help.
    if ($route_name === 'views.ajax') {
      $request = $this->requestStack->getCurrentRequest();
      if ($request && $request->query->get('media_library_opener_id')) {
        return TRUE;
      }
      $view = isset($variables['view']) && $variables['view'] instanceof ViewExecutable;
      if ($view && $variables['view']->id() === 'media_library') {
        return TRUE;
      }
    }

    return FALSE;
  }

}
