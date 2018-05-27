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

    // TODO add to configuration
    $default = [
      'loan_amount' => '',
      'annual_interest_rate' => '',
      'loan_period' => '',
      'payments_per_year' => '',
      'loan_start_date' => '',
      'optional_extra_payments' => '',
    ];

    // Form elements
    $form['loan_amount'] = [
      '#type' => 'number',
      '#title' => $this->t('Loan amount'),
      '#default_value' => isset($form_data['loan_amount']) ? $form_data['loan_amount'] : $default['loan_amount'],

    ];
    $form['annual_interest_rate'] = [
      '#type' => 'number',
      '#title' => $this->t('Annual Interest Rate'),
      '#default_value' => isset($form_data['annual_interest_rate']) ? $form_data['annual_interest_rate'] : $default['annual_interest_rate'],
    ];
    $form['loan_period'] = [
      '#type' => 'number',
      '#title' => $this->t('Loan Period in Years'),
      '#default_value' => isset($form_data['loan_period']) ? $form_data['loan_period'] : $default['loan_period'],
    ];
    $form['payments_per_year'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of Payments Per Year'),
      '#default_value' => isset($form_data['payments_per_year']) ? $form_data['payments_per_year'] : $default['payments_per_year'],
    ];
    $form['loan_start_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Start Date of Loan'),
      '#default_value' => isset($form_data['loan_start_date']) ? $form_data['loan_start_date'] : $default['loan_start_date'],
    ];
    $form['optional_extra_payments'] = [
      '#type' => 'number',
      '#title' => $this->t('Optional Extra Payments'),
      '#default_value' => isset($form_data['optional_extra_payments']) ? $form_data['optional_extra_payments'] : $default['optional_extra_payments'],
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Calculate'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('loan_amount')) < 3) {
      $form_state->setErrorByName('loan_amount', $this->t('The phone number is too short. Please enter a full phone number.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message($this->t('Your phone number is @number', ['@number' => $form_state->getValue('loan_amount')]));

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

    $form_state->setRedirect('loan_payments.content', [], ['query' => $result]);
  }
}