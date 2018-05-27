<?php

namespace Drupal\loan_payments\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for ice cream flavor plugins.
 */
interface LoanPaymentsInterface extends PluginInspectionInterface {

  /**
   * @return string
   */
  public function getLoanAmount();

  /**
   * @param string $loanAmount
   */
  public function setLoanAmount($loanAmount);

  /**
   * @return string
   */
  public function getAnnualInterestRate();

  /**
   * @param string $annualInterestRate
   */
  public function setAnnualInterestRate($annualInterestRate);

  /**
   * @return string
   */
  public function getLoanPeriod();

  /**
   * @param string $loanPeriod
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
   * @return string
   */
  public function getOptionalExtraPayments();

  /**
   * @param string $optionalExtraPayments
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
   * @return int
   */
  public function getTotalInterest();
}