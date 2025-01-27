<?php

namespace Drupal\typed_data\Plugin\TypedDataFormWidget;

use Drupal\Core\Form\SubformStateInterface;
use Drupal\Core\Plugin\Context\ContextDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinitionInterface;
use Drupal\Core\TypedData\ListInterface;
use Drupal\Core\TypedData\OptionsProviderInterface;
use Drupal\Core\TypedData\TypedDataInterface;
use Drupal\typed_data\Attribute\TypedDataFormWidget;
use Drupal\typed_data\Form\SubformState;
use Drupal\typed_data\Widget\FormWidgetBase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Plugin implementation of the 'select' widget.
 *
 * @TypedDataFormWidget(
 *   id = "select",
 *   label = @Translation("Select"),
 *   description = @Translation("A simple select box."),
 * )
 */
#[TypedDataFormWidget(
  id: "select",
  label: new TranslatableMarkup("Select"),
  description: new TranslatableMarkup("A simple select box.")
)]
class SelectWidget extends FormWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return parent::defaultConfiguration() + [
      'label' => NULL,
      'description' => NULL,
      'empty_option' => NULL,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isApplicable(DataDefinitionInterface $definition): bool {
    return is_subclass_of($definition->getClass(), OptionsProviderInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  public function form(TypedDataInterface $data, SubformStateInterface $form_state): array {
    assert($data instanceof OptionsProviderInterface);
    $form = SubformState::getNewSubForm();
    $form['value'] = [
      '#type' => 'select',
      '#title' => $this->configuration['label'] ?: $data->getDataDefinition()->getLabel(),
      '#description' => $this->configuration['description'] ?: $data->getDataDefinition()->getDescription(),
      '#default_value' => $data->getValue(),
      '#multiple' => $data instanceof ListInterface,
      '#empty_option' => $this->configuration['empty_option'],
      '#empty_value' => '',
      '#required' => $data->getDataDefinition()->isRequired(),
      '#disabled' => $data->getDataDefinition()->isReadOnly(),
      '#options' => $data->getSettableOptions(),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function extractFormValues(TypedDataInterface $data, SubformStateInterface $form_state): void {
    // Ensure empty values correctly end up as NULL value.
    $value = $form_state->getValue('value');
    if ($value === '') {
      $value = NULL;
    }
    $data->setValue($value);
  }

  /**
   * {@inheritdoc}
   */
  public function flagViolations(TypedDataInterface $data, ConstraintViolationListInterface $violations, SubformStateInterface $formState): void {
    foreach ($violations as $violation) {
      /** @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
      $formState->setErrorByName('value', $violation->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigurationDefinitions(DataDefinitionInterface $definition): array {
    return [
      'label' => ContextDefinition::create('string')
        ->setLabel($this->t('Label')),
      'description' => ContextDefinition::create('string')
        ->setLabel($this->t('Description')),
      'empty_option' => ContextDefinition::create('string')
        ->setLabel($this->t('Empty option label'))
        ->setDescription($this->t('Allows overriding the label of the empty option')),
    ];
  }

}
