<?php

namespace Drupal\loan_payments\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements an loan_payment form.
 */
class LoanPaymentsForm extends FormBase {

  public function getFormId() {
    return 'loan_payment_calculator';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $form_data = NULL) {
    // Submitted data
    $form_data = (array) json_decode($form_data);

    // Default configs
    $default_config = \Drupal::config('loan_payments.settings');
    $default = [
      'loan_amount' => $default_config->get('form.loan_amount'),
      'annual_interest_rate' => $default_config->get('form.annual_interest_rate'),
      'loan_period' => $default_config->get('form.loan_period'),
      'payments_per_year' => $default_config->get('form.payments_per_year'),
      'loan_start_date' => '',
      'optional_extra_payments' => '',
    ];

    // Form elements
    $form['loan_amount'] = [
      '#type' => 'number',
      '#title' => $this->t('Loan amount (USD)'),
      '#default_value' => isset($form_data['loan_amount']) ? $form_data['loan_amount'] : $default['loan_amount'],
      '#required' => TRUE,
    ];
    $form['annual_interest_rate'] = [
      '#type' => 'number',
      '#title' => $this->t('Annual Interest Rate (%)'),
      '#default_value' => isset($form_data['annual_interest_rate']) ? $form_data['annual_interest_rate'] : $default['annual_interest_rate'],
      '#required' => TRUE,
    ];
    $form['loan_period'] = [
      '#type' => 'number',
      '#title' => $this->t('Loan Period in Years'),
      '#default_value' => isset($form_data['loan_period']) ? $form_data['loan_period'] : $default['loan_period'],
      '#required' => TRUE,
    ];
    $form['payments_per_year'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of Payments Per Year'),
      '#default_value' => isset($form_data['payments_per_year']) ? $form_data['payments_per_year'] : $default['payments_per_year'],
      '#required' => TRUE,
    ];
//    $form['loan_start_date'] = [
//      '#type' => 'date',
//      '#title' => $this->t('Start Date of Loan'),
//      '#default_value' => isset($form_data['loan_start_date']) ? $form_data['loan_start_date'] : $default['loan_start_date'],
//    ];
//    $form['optional_extra_payments'] = [
//      '#type' => 'number',
//      '#title' => $this->t('Optional Extra Payments'),
//      '#default_value' => isset($form_data['optional_extra_payments']) ? $form_data['optional_extra_payments'] : $default['optional_extra_payments'],
//    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Calculate'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (
      ($form_state->getValue('loan_amount') <= 0) ||
      ($form_state->getValue('annual_interest_rate') <= 0) ||
      ($form_state->getValue('loan_period') <= 0) ||
      ($form_state->getValue('payments_per_year') <= 0)
    ) {
      $form_state->setErrorByName('loan_amount', $this->t('Number must be positive.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $result = [
      'form_data' => json_encode([
        'loan_amount' => $form_state->getValue('loan_amount'),
        'annual_interest_rate' => $form_state->getValue('annual_interest_rate'),
        'loan_period' => $form_state->getValue('loan_period'),
        'payments_per_year' => $form_state->getValue('payments_per_year'),
        'loan_start_date' => $form_state->getValue('loan_start_date'),
        'optional_extra_payments' => $form_state->getValue('optional_extra_payments'),
      ]),
    ];

    \Drupal::logger('loan_payment')->notice('Loan calculator submited with values: %output.',
      [
        '%output' => json_encode($result),
      ]);

    $form_state->setRedirect('loan_payments.content', [], ['query' => $result]);
    drupal_set_message($this->t('Successfully calculated'));
  }
}
