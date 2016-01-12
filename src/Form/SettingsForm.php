<?php

/**
 * @file
 * Contains \Drupal\flood_control\Form\SettingsForm.
 */

namespace Drupal\flood_control\Form;

use Drupal\Core\Config\Config;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Class SettingsForm.
 *
 * @package Drupal\flood_control\Form
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $config = $this->config('flood_control.settings');
    print_r($config); exit;
    $form['login'] = array(
        '#type' => 'fieldset',
        '#title' => t('Login'),
        '#access' => \Drupal::currentUser()->hasPermission('administer users'),
    );
    $form['login']['user_failed_login_ip_limit'] = array(
        '#type' => 'select',
        '#title' => t('Failed login (IP) limit'),
        '#options' => array_combine(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 20, 30, 40, 50, 75, 100, 125, 150, 200, 250, 500),
            array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 20, 30, 40, 50, 75, 100, 125, 150, 200, 250, 500)),
        '#default_value' => $config->get('user_failed_login_ip_limit', 50),
    );
    $form['login']['user_failed_login_ip_window'] = array(
        '#type' => 'select',
        '#title' => t('Failed login (IP) window'),
        '#options' => array(0 => t('None (disabled)')) + array_combine(array(60, 180, 300, 600, 900, 1800, 2700, 3600, 10800, 21600, 32400, 43200, 86400),
                array(60, 180, 300, 600, 900, 1800, 2700, 3600, 10800, 21600, 32400, 43200, 86400), 'format_interval'),
        '#default_value' => $config->get('user_failed_login_ip_window', 3600),
    );
    $form['login']['user_failed_login_user_limit'] = array(
        '#type' => 'select',
        '#title' => t('Failed login (username) limit'),
        '#options' => array_combine(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 20, 30, 40, 50, 75, 100, 125, 150, 200, 250, 500),
            array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 20, 30, 40, 50, 75, 100, 125, 150, 200, 250, 500)),
        '#default_value' => $config->get('user_failed_login_user_limit', 5),
    );
    $form['login']['user_failed_login_user_window'] = array(
        '#type' => 'select',
        '#title' => t('Failed login (username) window'),
        '#options' => array(0 => t('None (disabled)')) + array_combine(array(60, 180, 300, 600, 900, 1800, 2700, 3600, 10800, 21600, 32400, 43200, 86400),
                array(60, 180, 300, 600, 900, 1800, 2700, 3600, 10800, 21600, 32400, 43200, 86400), 'format_interval'),
        '#default_value' => $config->get('user_failed_login_user_window', 21600),
    );

    // Contact module flood events.
    $form['contact'] = array(
        '#type' => 'fieldset',
        '#title' => t('Contact forms'),
        '#access' => \Drupal::currentUser()->hasPermission('administer contact forms'),
    );
    $form['contact']['contact_threshold_limit'] = array(
        '#type' => 'select',
        '#title' => t('Sending e-mails limit'),
        '#options' => array_combine(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 20, 30, 40, 50, 75, 100, 125, 150, 200, 250, 500),
            array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 20, 30, 40, 50, 75, 100, 125, 150, 200, 250, 500)),
        '#default_value' => $config->get('contact_threshold_limit', 5),
    );
    $form['contact']['contact_threshold_window'] = array(
        '#type' => 'select',
        '#title' => t('Sending e-mails window'),
        '#options' => array(0 => t('None (disabled)')) + array_combine(array(60, 180, 300, 600, 900, 1800, 2700, 3600, 10800, 21600, 32400, 43200, 86400),
                array(60, 180, 300, 600, 900, 1800, 2700, 3600, 10800, 21600, 32400, 43200, 86400),'format_interval'),
        '#default_value' => $config->get('contact_threshold_window', 3600),
    );

    // Show a message if the user does not have any access to any options.
    if (!Element::getVisibleChildren($form)) {
      $form['nothing'] = array(
          '#markup' => '<p>' . t('Sorry, there are no flood control options for you to configure.') . '</p>',
      );
      return $form;
    }
    else {
      return $form;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('flood_control.settings')
      ->set('user_failed_login_ip_limit', $form_state->getValue('user_failed_login_ip_limit'))
      ->set('user_failed_login_ip_window', $form_state->getValue('user_failed_login_ip_window'))
      ->set('user_failed_login_user_limit', $form_state->getValue('user_failed_login_user_limit'))
      ->set('user_failed_login_user_window', $form_state->getValue('user_failed_login_user_window'))
      ->set('contact_threshold_limit', $form_state->getValue('contact_threshold_limit'))
      ->set('contact_threshold_window', $form_state->getValue('contact_threshold_window'))->save();
    return parent::submitForm();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'flood_control.settings',
    ];
  }

}
