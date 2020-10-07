<?php
use CRM_Orgrelationshiptab_ExtensionUtil as E;

class CRM_Orgrelationshiptab_Page_Orgrelationshiptab extends CRM_Core_Page /*CRM_Contact_Page_View_Relationship*/ {

  use CRM_Core_Page_EntityPageTrait;

  // doesn't work
  //public $useLivePageJS = TRUE;

  public static $_links = NULL;


  /**
   * Explicitly declare the entity api name.
   *
   * @return string
   */
  public function getDefaultEntity() {
    return 'Relationship';
  }

  /**
   * Explicitly declare the form context.
   *
   * @return string|null
   */
  public function getDefaultContext() {
    return 'search';
  }



  public function run() {

    $this->preProcess();

    $this->setContext();

    // displaying info
    $this->browse();

    return parent::run();
  }

  function preProcess() {

    $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this);
    $this->_contactId = CRM_Utils_Request::retrieve('cid', 'Positive', $this, TRUE);
    $this->assign('contactId', $this->_contactId);

    $this->_action = CRM_Utils_Request::retrieve('action', 'String', $this, FALSE, 'browse');
    $this->assign('action', $this->_action);

    // check logged in url permission
    CRM_Contact_Page_View::checkUserPermission($this);

    // set page title
    CRM_Contact_Page_View::setTitle($this->_contactId);

  }

  function setContext() {
    // to refresh the page
    $url = CRM_Utils_System::url('civicrm/contact/view', "action=browse&selectedChild=orgrelationship&reset=1&cid={$this->_contactId}");
    $session = CRM_Core_Session::singleton();
    $session->pushUserContext($url);
  }

  function browse() {

    $tree = CRM_Orgrelationshiptab_Utils::getOrgHierarchy($this->_contactId, self::links());
    $this->assign('tree', $tree);


    // for highlighting current contact
    $this->assign('current', $this->_contactId);

  }

  /**
   * called to delete the relationship of a contact.
   *
   */
  public function delete() {
    // calls a function to delete relationship
    CRM_Contact_BAO_Relationship::del($this->getEntityId());
  }


  /**
   * Get action links.
   *
   * @return array
   *   (reference) of action links
   */
  public static function &links() {

    // copy/paste from Relationship tab
    if (!(self::$_links)) {
      $disableExtra = ts('Are you sure you want to disable this relationship?');

      self::$_links = array(
        CRM_Core_Action::VIEW => array(
          'name' => ts('View'),
          'url' => 'civicrm/contact/view/rel',
          'qs' => 'action=view&reset=1&cid=%%cid%%&id=%%id%%&rtype=%%rtype%%&selectedChild=rel',
          'title' => ts('View Relationship'),
        ),
        CRM_Core_Action::UPDATE => array(
          'name' => ts('Edit'),
          'url' => 'civicrm/contact/view/rel',
          'qs' => 'action=update&reset=1&cid=%%cid%%&id=%%id%%&rtype=%%rtype%%',
          'title' => ts('Edit Relationship'),
        ),
        CRM_Core_Action::DELETE => array(
          'name' => ts('Delete'),
          'url' => 'civicrm/contact/view/rel',
          'qs' => 'action=delete&reset=1&cid=%%cid%%&id=%%id%%&rtype=%%rtype%%',
          'title' => ts('Delete Relationship'),
        ),
      );
    }
    return self::$_links;
  }

}
