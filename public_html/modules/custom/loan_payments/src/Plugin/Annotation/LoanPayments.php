<?php

namespace Drupal\loan_payments\Plugin\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a loan payments item annotation object.

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
   * @var float
   */
  public $loanAmount;

  /**
   * Annual Interest Rate
   *
   * @var float
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
   * @var float
   */
  public $optionalExtraPayments;

  /**
   * @var int
   */
  public $scheduledPaymentNumbers;

}