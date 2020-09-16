<?php
use CRM_Orgrelationshiptab_ExtensionUtil as E;

class CRM_Orgrelationshiptab_Page_Ajax {

  // same as CRM_Contact_Page_AJAX::getContactRelationships 
  // but remove what has been moved in the organization relations tab
  public function getOtherRelationships() {

    $contactID = CRM_Utils_Type::escape($_GET['cid'], 'Integer');
    $context = CRM_Utils_Request::retrieve('context', 'Alphanumeric');
    $relationship_type_id = CRM_Utils_Type::escape(CRM_Utils_Array::value('relationship_type_id', $_GET), 'Integer', FALSE);

    if (!CRM_Contact_BAO_Contact_Permission::allow($contactID)) {
      return CRM_Utils_System::permissionDenied();
    }

    $params = CRM_Core_Page_AJAX::defaultSortAndPagerParams();

    $params['contact_id'] = $contactID;
    $params['context'] = $context;
    if ($relationship_type_id) {
      $params['relationship_type_id'] = $relationship_type_id;
    }

    // get the contact relationships
    $relationships = CRM_Contact_BAO_Relationship::getContactRelationshipSelector($params);

    // only filter for organization
    if (CRM_Contact_BAO_Contact::getContactType($contactID) == 'Organization') {

      // simpler and safer to use the regular getter and remove the relationship we don't want
      $sql = "
        SELECT r.id 
        FROM civicrm_relationship r
          INNER JOIN civicrm_contact ca ON r.contact_id_a = ca.id
          INNER JOIN civicrm_contact cb ON r.contact_id_b = cb.id
        WHERE 
             (contact_id_a = %1 AND cb.contact_type = 'Organization')
          OR (contact_id_b = %1 AND ca.contact_type = 'Organization')";
      $dao = CRM_Core_DAO::executeQuery($sql, [1 => [$contactID, 'Integer']]);
      $excludeIds = [];
      while ($dao->fetch()) {
        $excludeIds[] = $dao->id;
      }
      foreach ($relationships['data'] as $idx => $relationship) {
        if (in_array($relationship['DT_RowId'], $excludeIds)) {
          // exclude that relationship as it should be in the other org tab
          unset($relationships['data'][$idx]);
          $relationships['recordsTotal'] -= 1;
          $relationships['recordsFiltered'] -= 1;
        }
      }
      // reindexing as we might have removed items
      $relationships['data'] = array_values($relationships['data']);
    }

    CRM_Utils_JSON::output($relationships);

  }

}
