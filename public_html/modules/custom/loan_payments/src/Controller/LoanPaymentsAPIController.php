<?php

namespace Drupal\loan_payments\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller routines for loan-payments routes.
 */
class LoanPaymentsAPIController extends ControllerBase {

  public function content(Request $request) {

    $form_data = $request->query->get('form_data');
    $form = \Drupal::formBuilder()
      ->getForm('Drupal\loan_payments\Form\LoanPaymentsForm', $form_data);
    dpm($form_data);
    // Loan_Years*Num_Pmt_Per_Year
    $summary = $this->getSummary($form_data);
    //    $summary = [
    //      'scheduled_numbers_of_payments' =>
    //    ];
    dpm($summary);
    return [
      '#theme' => 'loan_output',
      '#form' => render($form),
      '#summary' => $summary,
      '#list' => 'list',
    ];
  }

  private function getSummary($form_data) {
    $result = '';
    // Submitted data
    $form_data = (array) json_decode($form_data);
    // Calculation
    dpm($form_data);
    $sheduledPayments = (int) $form_data['payments_per_year'] * (int) $form_data['loan_period'];
    $interestRate = (int) $form_data['annual_interest_rate'] * 100;
    $beginBalance = (int) $form_data['loan_amount'];

    $result = [
      'scheduled_numbers_of_payments' => [
        'label' => t('Scheduled Number of Payments'),
        'value' => $sheduledPayments,
      ],
      'total_interest' => [
        'label' => t('Total Interest'),
        'value' => $this->getTotalInterest($form_data),
      ],
    ];

    return $result;
  }

  private function getTotalInterest($form_data) {
    $sheduledPayments = (int) $form_data['payments_per_year'] * (int) $form_data['loan_period'];
    $interestRate = (int) $form_data['annual_interest_rate'] / 100;
    $beginBalance = (int) $form_data['loan_amount'];
    $paymentsPerYear = (int) $form_data['payments_per_year'];
    $cumulativeInterest = 0;
    for ($i = 0; $i < $sheduledPayments; ++$i) {
      $beginBalance -= $cumulativeInterest;
      $cumulativeInterest += $beginBalance * ($interestRate / $paymentsPerYear);
//      dpm($beginBalance * $interestRate / $paymentsPerYear);
//      dpm($beginBalance);
    }
    return (int) $cumulativeInterest;
  }
}
