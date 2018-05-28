<?php

namespace Drupal\loan_payments\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'LoanPaymentsBlock' Block.
 *
 * @Block(
 *   id = "loan_payments_block",
 *   admin_label = @Translation("Loan payments block"),
 * )
 */
class LoanPaymentsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Prepare form
    $form_data = \Drupal::request()->get('form_data');
    $form = \Drupal::formBuilder()
      ->getForm('Drupal\loan_payments\Form\LoanPaymentsForm');
    $build = [
      '#theme' => 'loan_output',
      '#form' => render($form),
    ];

    // Prepare output
    if (!empty($form_data)) {
      $form_data = (array) json_decode($form_data);
      $manager = \Drupal::service('plugin.manager.loan_payments_api');
      $plugin = $manager->createInstance('calculator');
      $plugin->setData($form_data);

      $summary = $this->getSummary($plugin);
      $build['#summary'] = $summary;

      $list = $plugin->getPaymentList();
      $build['#list'] = $list;
    }

    return $build;
  }

  private function getSummary($plugin) {
    $result = [
      'scheduled_payment' => [
        'label' => t('Scheduled payment'),
        'value' => $plugin->getScheduledPayment(),
      ],
      'scheduled_numbers_of_payments' => [
        'label' => t('Scheduled Number of Payments'),
        'value' => $plugin->getScheduledPaymentNumbers(),
      ],
      'total_interest' => [
        'label' => t('Total Interest'),
        'value' => $plugin->getTotalInterest(),
      ],
    ];
    return $result;
  }
}