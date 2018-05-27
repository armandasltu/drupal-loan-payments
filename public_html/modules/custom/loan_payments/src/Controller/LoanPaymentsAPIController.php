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

  public static function create(ContainerInterface $container) {
    return new static($container->get('module_handler'));
  }

  public function content(Request $request) {
    $form_data = $request->query->get('form_data');
    $form = \Drupal::formBuilder()
      ->getForm('Drupal\loan_payments\Form\LoanPaymentsForm', $form_data);

    $form_data = (array) json_decode($form_data);
    $manager = \Drupal::service('plugin.manager.loan_payments_api');
    $plugin = $manager->createInstance('calculator');
    $plugin->setData($form_data);

    $summary = $this->getSummary($plugin);

    $list = $plugin->getPaymentList();

    return [
      '#theme' => 'loan_output',
      '#form' => render($form),
      '#summary' => $summary,
      '#list' => $list,
    ];
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
