{*
 +--------------------------------------------------------------------+
 | CiviCRM version 5                                                  |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2019                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*}
{* relationship selector *}
{crmRegion name="crm-contact-relationshipselector-pre"}
{/crmRegion}

<div class="crm-contact-relationship-{$context}">
  <table
    class="crm-contact-relationship-selector-{$context} crm-ajax-table"
    data-ajax="{crmURL p="civicrm/orgrelationship/ajax/orgrelationships" q="context=$context&cid=$contactId&relationship_type_id=$relationship_type_id"}" style="width: 100%;">
    <thead>
    <tr>
      <th data-data="relation" class='crm-contact-relationship-type'>{ts}Relationship{/ts}</th>
      <th data-data="sort_name" class='crm-contact-relationship-contact_name'>&nbsp;</th>
      <th data-data="start_date" class='crm-contact-relationship-start_date'>{ts}Start{/ts}</th>
      <th data-data="end_date" class='crm-contact-relationship-end_date'>{ts}End{/ts}</th>
      <th data-data="city" class='crm-contact-relationship-city'>{ts}City{/ts}</th>
      <th data-data="state" class='crm-contact-relationship-state'>{ts}State/Prov{/ts}</th>
      <th data-data="email" class='crm-contact-relationship-email'>{ts}Email{/ts}</th>
      <th data-data="phone" class='crm-contact-relationship-phone'>{ts}Phone{/ts}</th>
      <th data-data="links" data-orderable="false" class='crm-contact-relationship-links'></th>
    </tr>
    </thead>
  </table>
</div>
{crmRegion name="crm-contact-relationshipselector-post"}
{/crmRegion}

