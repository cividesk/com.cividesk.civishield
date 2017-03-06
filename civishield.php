<?php

/**
 * Implementation of hook_civicrm_postProcess
 *
 * When a contribution page has been successfully validated, save the qfKey
 */

function civishield_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_Contribute_Form_Contribution_Confirm') {
    // get the qfKey from the form variables
    $values = $form->getVar('_submitValues');
    $qfKey = CRM_Utils_Array::value('qfKey', $values);
    // save it in the CiviCRM database cache
    $cache = array(
      'form_id' => $form->getVar('_id'),
      'time' => time(),
    );
    CRM_Core_BAO_Cache::setItem($cache, 'CiviShield', $qfKey);
  }
}

/**
 * Implementation of hook_civicrm_validate
 *
 * Check that the same qfKey was not used on a previous submission
 */
function civishield_civicrm_validate($formName, &$fields, &$files, &$form) {
  if ($formName == 'CRM_Contribute_Form_Contribution_Confirm') {
    // get the qfKey from the form variables
    $values = $form->getVar('_submitValues');
    $qfKey = CRM_Utils_Array::value('qfKey', $values);
    // see if there is any entry in the database cache with the same qfKey
    $cache = CRM_Core_BAO_Cache::getItem('CiviShield', $qfKey);
    if ($cache !== NULL) {
      $last_time = CRM_Utils_Array::value('time', $cache);
      // was the last entry less than an hour ago?
      if ($last_time > strtotime('-1 hour')) {
        // save all details on the suspicious entry in the log file
        $entry = array(
          'date' => date('Y-m-d H:i:s'),
          'form_id' => $form->getVar('_id'),
          'qfKey' => $qfKey,
          'last_time' => date('Y-m-d H:i:s', $last_time),
          'last_form_id' => CRM_Utils_Array::value('form_id', $cache),
          '$_server' => $_SERVER,
        );
        $config = CRM_Core_Config::singleton();
        file_put_contents($config->configAndLogDir . 'civishield_log', print_r($entry, TRUE) . PHP_EOL, FILE_APPEND);
        // and abort the current transaction with a fatal
        CRM_Core_Error::fatal('CiviShield: suspicious transaction detected, please contact the site owner if this is an error.');
      }
    }
  }
}