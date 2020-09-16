{* This tpl runs recursively to build each level of the tag tree *}
<ul class="tree-level-{$level}">
  {foreach from=$tree item="node" key="id"}
    <li id="orgli_{$id}" class="data crm-entity{if $node.data.id eq $current} current{/if}" data-entity="relationship" data-id="{$id}">
      <span class="content">
        <span>{$node.data.rel_name}</span>&nbsp;
        {if $node.data.id eq $current}
          <strong>{$node.data.display_name}</strong>&nbsp;[{$node.data.id}]
        {else}
          <a href="{$node.data.view_url}">{$node.data.display_name}</a>&nbsp;[<a href="{$node.data.view_url}">{$node.data.id}</a>]
        {/if}
        <span class="actions">{$node.action}</span>
      </span>
      {if $node.children}
        {* Recurse... *}
        {include file="CRM/Orgrelationshiptab/Page/OrgrelationshipTree.tpl" tree=$node.children level=$level+1}
      {/if}
    </li>
  {/foreach}
</ul>
