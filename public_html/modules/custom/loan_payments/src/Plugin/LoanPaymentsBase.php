<?php

namespace Drupal\loan_payments\Plugin;

use Drupal\Component\Plugin\PluginBase;

class LoanPaymentsBase extends PluginBase implements LoanPaymentsInterface {


  /**
   * @return float
   */
  public function getLoanAmount() {
    return $this->pluginDefinition['loanAmount'];
  }

  /**
   * @param float $loanAmount
   */
  public function setLoanAmount($loanAmount) {
    $this->pluginDefinition['loanAmount'] = $loanAmount;
  }

  /**
   * @return float
   */
  public function getAnnualInterestRate() {
    return $this->pluginDefinition['annualInterestRate'];
  }

  /**
   * @param float $annualInterestRate
   */
  public function setAnnualInterestRate($annualInterestRate) {
    $this->pluginDefinition['annualInterestRate'] = $annualInterestRate;
  }

  /**
   * @return int
   */
  public function getLoanPeriod() {
    return $this->pluginDefinition['loanPeriod'];
  }

  /**
   * @param int $loanPeriod
   */
  public function setLoanPeriod($loanPeriod) {
    $this->pluginDefinition['loanPeriod'] = $loanPeriod;
  }

  /**
   * @return int
   */
  public function getPaymentsPerYear() {
    return $this->pluginDefinition['paymentsPerYear'];
  }

  /**
   * @param int $paymentsPerYear
   */
  public function setPaymentsPerYear($paymentsPerYear) {
    $this->pluginDefinition['paymentsPerYear'] = $paymentsPerYear;
  }

  /**
   * @return string
   */
  public function getLoanStartDate() {
    return $this->pluginDefinition['loanStartDate'];
  }

  /**
   * @param string $loanStartDate
   */
  public function setLoanStartDate($loanStartDate) {
    $this->pluginDefinition['loanStartDate'] = $loanStartDate;
  }

  /**
   * @return int
   */
  public function getOptionalExtraPayments() {
    return $this->pluginDefinition['optionalExtraPayments'];
  }

  /**
   * @param int $optionalExtraPayments
   */
  public function setOptionalExtraPayments($optionalExtraPayments) {
    $this->pluginDefinition['optionalExtraPayments'] = $optionalExtraPayments;
  }

  /**
   * @return int
   */
  public function getScheduledPaymentNumbers() {
    return $this->pluginDefinition['scheduledPaymentNumbers'];
  }

  /**
   * @return float
   */
  public function getScheduledPayment() {
    return $this->pluginDefinition['scheduledPayment'];
  }

  /**
   * @return float
   */
  public function getTotalInterest() {
    return $this->pluginDefinition['totalInterest'];
  }

  /**
   * @param array $form_data
   */
  public function setData($form_data) {
    if (isset($form_data['loan_amount'])) {
      $this->setLoanAmount($form_data['loan_amount']);
    }
    if (isset($form_data['annual_interest_rate'])) {
      $this->setAnnualInterestRate($form_data['annual_interest_rate']);
    }
    if (isset($form_data['loan_period'])) {
      $this->setLoanPeriod($form_data['loan_period']);
    }
    if (isset($form_data['payments_per_year'])) {
      $this->setPaymentsPerYear($form_data['payments_per_year']);
    }
    if (isset($form_data['loan_start_date'])) {
      $this->setLoanStartDate($form_data['loan_start_date']);
    }
    if (isset($form_data['optional_extra_payments'])) {
      $this->setOptionalExtraPayments($form_data['optional_extra_payments']);
    }
    // Calculation
    $this->pluginDefinition['scheduledPaymentNumbers'] = (int) $this->getPaymentsPerYear() * (int) $this->getLoanPeriod();
    $this->calculateScheduledPayment();
    $this->calculateTotalInterest();
  }

  public function calculateTotalInterest() {
    $interestRate = (float) $this->getAnnualInterestRate() / 100;
    $beginBalance = (float) $this->getLoanAmount();
    $totalInterest = 0;

    for ($i = 0; $i < $this->getScheduledPaymentNumbers(); ++$i) {
      $interest = $beginBalance * $interestRate / $this->getPaymentsPerYear();
      $totalInterest += $interest;
      $beginBalance = $beginBalance - $this->getScheduledPayment() + $interest;
    }

    $this->pluginDefinition['totalInterest'] = (float) number_format($totalInterest, 2, '.', '');
  }


  /**
   * @return array
   */
  public function getPaymentList() {
    $interestRate = (float) $this->getAnnualInterestRate() / 100;
    $beginningBalance = (float) $this->getLoanAmount();
    $endingBalance = $beginningBalance;
    $cumulativeInterest = 0;
    $result = [];

    for ($i = 1; $i <= $this->getScheduledPaymentNumbers(); ++$i) {
      $interest = $endingBalance * $interestRate / $this->getPaymentsPerYear();
      $cumulativeInterest += $interest;
      $endingBalance = $endingBalance - $this->getScheduledPayment() + $interest;
      $result[] = [
        'payment_number' => $i,
        'beginning_balance' => number_format($beginningBalance, 2, '.', ''),
        'scheduled_payment' => $this->getScheduledPayment(),
        'interest' => number_format($interest, 2, '.', ''),
        'ending_balance' => ($endingBalance > 0) ? number_format($endingBalance, 2, '.', '') : 0,
        'cumulative_interest' => (float) number_format($cumulativeInterest, 2, '.', ''),
      ];
      $beginningBalance = $endingBalance;
    }

    return $result;
  }

  /**
   * =PMT(R,n,Pv) function: P = (Pv*R) / [1 - (1 + R)^(-n)]
   */
  public function calculateScheduledPayment() {
    $interestRate = (int) $this->getAnnualInterestRate() / 100;
    $result = (($this->getLoanAmount() * ($interestRate / $this->getPaymentsPerYear())) / (1 - (pow((1 + ($interestRate / $this->getPaymentsPerYear())), (-1 * $this->getScheduledPaymentNumbers())))));
    $this->pluginDefinition['scheduledPayment'] = (float) number_format($result, 2, '.', '');
  }


}