<?php

use CRM_Orgrelationshiptab_ExtensionUtil as E;

class CRM_Orgrelationshiptab_Utils {

  static public function getOrgHierarchy($contactId, $links) {

    // FIXME: some relation are the other way around - i.e. 
    $hiearchy = [];

    // 1. get root parents
    $roots = self::getRootParents($contactId, $relationship_type_id, 5);

    // 2. recursively go through the hierarchy
    foreach ($roots as $root) {
      $h = self::getHierarchy($root, $relationship_type_id, $links);
      $hierarchy[$root]['data'] = self::getOrgDetails($root);
      if (!empty($h)) {
        if (isset($hierarchy[$root]['children'])) {
          //Civi::log()->debug('vs ' . print_r($hierarchy[$root]['children'],1). '--' .var_export($h,1));
          $hierarchy[$root]['children'] += $h;
        }
        else {
          $hierarchy[$root]['children'] = $h;
        }
      }
    }

    return $hierarchy;

  }

  static protected function getRootParents($contactId, $relationship_type_id, $max) {
    // TODO:

    // avoid infinite loop
    // FIXME: in this case, the root is a bit random
    if ($max == 0) return [$contactId];

    $result = civicrm_api3('Relationship', 'get', [
      'sequential' => 1,
      'contact_id_b' => $contactId,
      //'relationship_type_id' => $relationship_type_id,
      'contact_id_a.contact_type' => "Organization",
      'return' => ['contact_id_a'],
      'option.limit' => 0,
    ]);

    // recursive if parent exist
    if ($result['count'] > 0 && isset($result['values'][0]['contact_id_a'])) {
      $items = [];
      foreach ($result['values'] as $rel) {
        $parent = $rel['contact_id_a'];
        $roots = self::getRootParents($parent, $relationship_type_id, $max-1);
        Civi::log()->debug('new roots -- ' . print_r($roots,1));
        $items = array_merge($items,$roots);
      }
      //Civi::log()->debug('items -- ' . $result['count'] . ' -- ' . print_r($items,1));
      return array_unique($items);
    }
    else {
      return [$contactId];
    }
  }

  static protected function getHierarchy($contactId, $relationship_type_id, $links, $max=5) {
    $hierarchy = [];

    if ($max == 0) return [];

    // API v4 is not working
/*    $relationships = \Civi\Api4\Relationship::get()
      ->addWhere('contact_id_a', '=', 3901) //$contactId)
      ->addWhere('relationship_type_id', '=', 11) //$relationship_type_id)
      ->setCurrent(TRUE)
      ->execute();*/

    // TODO: filter by contact type - only organization
    $relationships = civicrm_api3('Relationship', 'get', [
      'contact_id_a' => $contactId,
      'contact_id_b.contact_type' => "Organization",
      /*'relationship_type_id' => $relationship_type_id,*/
      'return' => ['id', 'contact_id_b', 'relationship_type_id.name_a_b'],
      'option.limit' => 0,
    ]);

    foreach ($relationships['values'] as $relationship) {
      $childId = $relationship['contact_id_b'];

      // relationship data
      $hierarchy[$childId]['relationship'] = $relationship;

      // contact data
      $details = self::getOrgDetails($childId);
      $hierarchy[$childId]['data'] = $details;
      $hierarchy[$childId]['data']['rel_name'] = $relationship['relationship_type_id.name_a_b'];

      // add actions
      $hierarchy[$childId]['action'] = self::getActions($links, $relationship['id'], $childId, 'b_a');

      // recursive
      $hierarchy[$childId]['children'] = self::getHierarchy($childId, $relationship_type_id, $links, $max-1);
      
    }

    return $hierarchy;

  }

  static public function getOrgDetails($contactId) {
    // TODO
    // API v4 is not working ??
    $contact = civicrm_api3('Contact', 'getsingle', [
      'id' => $contactId,
      'return' => ['display_name', 'id']
    ]);
    $contact['view_url'] = CRM_Utils_System::url('civicrm/contact/view', 'reset=1&cid=' . $contactId);
    
    return $contact;

    //return ['id' => $contactId];

  }

  static public function getActions($links, $relationshipId, $contactId, $rtype) {
    $action = array_sum(array_keys($links));
    return CRM_Core_Action::formLink($links, $action, 
      [
        'id' => $relationshipId,
        'cid' => $contactId,
        'rtype' => 'b_a',
      ], 
      ts('more'), FALSE, 'orgrel.list', 'Orgrelation', $relationshipId
    );
  }

}
