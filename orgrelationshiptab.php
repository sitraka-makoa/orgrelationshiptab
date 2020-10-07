<?php

require_once 'orgrelationshiptab.civix.php';
use CRM_Orgrelationshiptab_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/ 
 */
function orgrelationshiptab_civicrm_config(&$config) {
  _orgrelationshiptab_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function orgrelationshiptab_civicrm_xmlMenu(&$files) {
  _orgrelationshiptab_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function orgrelationshiptab_civicrm_install() {
  _orgrelationshiptab_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function orgrelationshiptab_civicrm_postInstall() {
  _orgrelationshiptab_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function orgrelationshiptab_civicrm_uninstall() {
  _orgrelationshiptab_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function orgrelationshiptab_civicrm_enable() {
  _orgrelationshiptab_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function orgrelationshiptab_civicrm_disable() {
  _orgrelationshiptab_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function orgrelationshiptab_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _orgrelationshiptab_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function orgrelationshiptab_civicrm_managed(&$entities) {
  _orgrelationshiptab_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function orgrelationshiptab_civicrm_caseTypes(&$caseTypes) {
  _orgrelationshiptab_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function orgrelationshiptab_civicrm_angularModules(&$angularModules) {
  _orgrelationshiptab_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function orgrelationshiptab_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _orgrelationshiptab_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function orgrelationshiptab_civicrm_entityTypes(&$entityTypes) {
  _orgrelationshiptab_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function orgrelationshiptab_civicrm_themes(&$themes) {
  _orgrelationshiptab_civix_civicrm_themes($themes);
}



/**
 * Implements hook_civicrm_tabset().
 */
function orgrelationshiptab_civicrm_tabset($tabsetName, &$tabs, $context) {
  if ($tabsetName == 'civicrm/contact/view') {
    $contactId = $context['contact_id'];

    if (CRM_Contact_BAO_Contact::getContactType($contactId) == 'Organization') {

      // update non org relationship count
      $sql = "
        SELECT count(*)
        FROM civicrm_relationship r
          INNER JOIN civicrm_contact ca ON r.contact_id_a = ca.id
          INNER JOIN civicrm_contact cb ON r.contact_id_b = cb.id
        WHERE r.is_active = 1 AND (
             (contact_id_a = %1 AND cb.contact_type != 'Organization')
          OR (contact_id_b = %1 AND ca.contact_type != 'Organization') )";
      $count = CRM_Core_DAO::singleValueQuery($sql, [1 => [$contactId, 'Integer']]);

      foreach($tabs as $idx => $tab) {
        if ($tab['id'] == 'rel') {
          $tabs[$idx]['count'] = $count;
          break;
        }
      }

      // add new tab
      $sql = "
        SELECT count(*)
        FROM civicrm_relationship r
          INNER JOIN civicrm_contact ca ON r.contact_id_a = ca.id
          INNER JOIN civicrm_contact cb ON r.contact_id_b = cb.id
        WHERE r.is_active = 1 AND (
             (contact_id_a = %1 AND cb.contact_type = 'Organization')
          OR (contact_id_b = %1 AND ca.contact_type = 'Organization') )";
      $count = CRM_Core_DAO::singleValueQuery($sql, [1 => [$contactId, 'Integer']]);

      $url = CRM_Utils_System::url( 'civicrm/orgrelationship/view/tab',
                                    "reset=1&snippet=1&force=1&cid=$contactId" );
      $tabs[] = [
        'id'    => 'orgrelationshiptab',
        'url'   => $url,
        'title' => E::ts('Organization relations'),
        'weight' => 335,
        'count' => $count,
      ];

      CRM_Core_Resources::singleton()->addStyleFile(E::LONG_NAME, 'css/orgrelationshiptab.css', 15, 'html-header');
    }

  }
} 


/**
 * Implements hook_civicrm_alterTemplateFile().
 */
function orgrelationshiptab_civicrm_alterTemplateFile($formName, &$form, $context, &$tplName) {

  if ($formName == 'CRM_Contact_Page_View_Relationship') {
    $contactId = $form->getVar('_contactId');
    if (CRM_Contact_BAO_Contact::getContactType($contactId) == 'Organization') {
      $possibleTpl = 'CRM/Orgrelationshiptab/Page/OtherRelationship.tpl';
      $template = CRM_Core_Smarty::singleton();
      if ($template->template_exists($possibleTpl)) {
        $tplName = $possibleTpl;
      }
//  Civi::log()->debug('orgrelationshiptab_civicrm_alterTemplateFile -- ' .print_r($form,1));
    }
  }

}

