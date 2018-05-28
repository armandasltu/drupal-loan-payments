<?php

namespace Drupal\loan_payments\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for ice cream flavor plugins.
 */
interface LoanPaymentsInterface extends PluginInspectionInterface {

  /**
   * @return float
   */
  public function getLoanAmount();

  /**
   * @param float $loanAmount
   */
  public function setLoanAmount($loanAmount);

  /**
   * @return float
   */
  public function getAnnualInterestRate();

  /**
   * @param float $annualInterestRate
   */
  public function setAnnualInterestRate($annualInterestRate);

  /**
   * @return int
   */
  public function getLoanPeriod();

  /**
   * @param int $loanPeriod
   */
  public function setLoanPeriod($loanPeriod);

  /**
   * @return int
   */
  public function getPaymentsPerYear();

  /**
   * @param int $paymentsPerYear
   */
  public function setPaymentsPerYear($paymentsPerYear);

  /**
   * @return string
   */
  public function getLoanStartDate();

  /**
   * @param string $loanStartDate
   */
  public function setLoanStartDate($loanStartDate);

  /**
   * @return float
   */
  public function getOptionalExtraPayments();

  /**
   * @param float $optionalExtraPayments
   */
  public function setOptionalExtraPayments($optionalExtraPayments);

  /**
   * @return int
   */
  public function getScheduledPaymentNumbers();

  /**
   * @param array $form_data
   */
  public function setData($form_data);

  /**
   * @return float
   */
  public function getTotalInterest();

  /**
   * @return array
   */
  public function getPaymentList();

}