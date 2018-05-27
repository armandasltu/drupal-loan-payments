<?php

namespace Drupal\loan_payments\Plugin\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a flavor item annotation object.
 *
 * Plugin Namespace: Plugin\icecream\flavor
 *
 * @see \Drupal\loan_payments\Plugin\LoanPaymentsManager
 * @see plugin_api
 *
 * @Annotation
 */
class LoanPayments extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * Loam amount
   *
   * @var int
   */
  public $loanAmount;

  /**
   * Annual Interest Rate
   *
   * @var int
   */
  public $annualInterestRate;

  /**
   * Loam period
   *
   * @var int
   */
  public $loanPeriod;

  /**
   * Payments per year
   *
   * @var int
   */
  public $paymentsPerYear;

  /**
   * @var int
   */
  public $loanStartDate;

  /**
   * @var int
   */
  public $optionalExtraPayments;

  /**
   * @var int
   */
  public $scheduledPaymentNumbers;

}