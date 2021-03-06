<?php

namespace Drupal\loan_payments\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for loan-payments routes.
 */
class LoanPaymentsAPIController extends ControllerBase implements ContainerInjectionInterface {
  public function content(Request $request) {
    // Prepare form
    $form_data = $request->query->get('form_data');
    $form = \Drupal::formBuilder()
      ->getForm('Drupal\loan_payments\Form\LoanPaymentsForm', $form_data);
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
