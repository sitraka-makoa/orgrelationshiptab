<div id="contact-summary-orgrelationship-tab" class="view-content">

  {if $permission EQ 'edit'}
    <div class="action-link">
     {crmButton accesskey="N"  p='civicrm/contact/view/rel' q="cid=`$contactId`&action=add&reset=1" icon="plus-circle"}{ts}Add Relationship{/ts}{/crmButton}
    </div>
  {/if}

  <h3>{ts}Current Relationships{/ts}</h3>

  <div class="crm-clear">
    {foreach from=$tree item="subtree" key="rellabel"}
      <h4>{$rellabel}</h4>
      <div class="reltree">
      {include file="CRM/Orgrelationshiptab/Page/OrgrelationshipTree.tpl" tree=$subtree level=0}
      </div>
    {/foreach}
  </div>

  <div class="spacer"></div>
  {* display past relationships *}
  <h3 class="font-red">{ts}Inactive Relationships{/ts}</h3>
  <div class="help">{ts}These relationships are Disabled OR have a past End Date.{/ts}</div>
  {include file="CRM/Orgrelationshiptab/Page/View/OrgrelationshipSelector.tpl" context="past"}
</div>


{literal}
<script type="text/javascript">
  CRM.$(function($) {
    // Changing relationships may affect related members and contributions. Ensure they are refreshed.
    $('#contact-summary-relationship-tab').on('crmPopupFormSuccess', function() {
      CRM.tabHeader.resetTab('#tab_contribute');
      CRM.tabHeader.resetTab('#tab_member');
    });
  });

  // http://civicrm.org/licensing
  // Adds ajaxy behavior to a simple CiviCRM page
  CRM.$(function($) {
    //var active = 'a.button, a.action-item, a.crm-popup';
    var active = "a.button, a.action-item:not(.crm-enable-disable), a.crm-popup';
    var cid = {/literal}{$contactId}{literal};
    $('#contact-summary-orgrelationship-tab')
      // Widgetize the content area
      .crmSnippet().crmSnippet('option', 'url', '/civicrm/orgrelationship/view/tab?reset=1&snippet=1&force=1&cid='+cid)
      // Open action links in a popup
      .off('.crmLivePage')
      .on('click.crmLivePage', active, CRM.popup)
      .on('crmPopupFormSuccess.crmLivePage', active, CRM.refreshParent);
  });

</script>
{/literal}
