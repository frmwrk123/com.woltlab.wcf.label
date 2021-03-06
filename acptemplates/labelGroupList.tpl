{include file='header'}

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		new WCF.Action.Delete('wcf\\data\\label\\group\\LabelGroupAction', '.jsLabelGroupRow');
		
		var options = { };
		{if $pages > 1}
			options.refreshPage = true;
			{if $pages == $pageNo}
				options.updatePageNumber = -1;
			{/if}
		{else}
			options.emptyMessage = '{lang}wcf.acp.label.group.noneAvailable{/lang}';
		{/if}
		
		new WCF.Table.EmptyTableHandler($('#labelGroupTableContainer'), 'jsLabelGroupRow', options);
	});
	//]]>
</script>

<header class="boxHeadline">
	<hgroup class="wcf-containerContent">
		<h1>{lang}wcf.acp.label.group.list{/lang}</h1>
	</hgroup>
</header>

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller="LabelGroupList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
	
	{hascontent}
		<nav>
			<ul>
				{content}
					{if $__wcf->session->getPermission('admin.content.label.canAddLabelGroup')}
						<li><a href="{link controller='LabelGroupAdd'}{/link}" title="{lang}wcf.acp.label.group.add{/lang}" class="button"><span class="icon icon16 icon-plus"></span> <span>{lang}wcf.acp.label.group.add{/lang}</span></a></li>
					{/if}
					
					{event name='contentNavigationButtonsTop'}
				{/content}
			</ul>
		</nav>
	{/hascontent}
</div>

{if $objects|count}
	<div id="labelGroupTableContainer" class="tabularBox tabularBoxTitle marginTop">
		<hgroup>
			<h1>{lang}wcf.acp.label.group.list{/lang} <span class="badge badgeInverse">{#$items}</span></h1>
		</hgroup>
		
		<table class="table">
			<thead>
				<tr>
					<th class="columnID columnLabelGroupID{if $sortField == 'groupID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link controller='LabelGroupList'}pageNo={@$pageNo}&sortField=groupID&sortOrder={if $sortField == 'groupID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}</a></th>
					<th class="columnTitle columnGroupName{if $sortField == 'groupName'} active {@$sortOrder}{/if}"><a href="{link controller='LabelGroupList'}pageNo={@$pageNo}&sortField=groupName&sortOrder={if $sortField == 'groupName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.label.group.groupName{/lang}</a></th>
					
					{event name='columnHeads'}
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item=group}
					<tr class="jsLabelGroupRow">
						<td class="columnIcon">
							{if $group->isEditable()}
								<a href="{link controller='LabelGroupEdit' object=$group}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 icon-pencil"></span></a>
							{/if}
							{if $group->isDeletable()}
								<span class="icon icon16 icon-remove jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$group->groupID}" data-confirm-message="{lang}wcf.acp.label.group.delete.sure{/lang}"></span>
							{/if}
							
							{event name='rowButtons'}
						</td>
						<td class="columnID"><p>{@$group->groupID}</p></td>
						<td class="columnTitle columnGroupName">{if $group->isEditable()}<p class="labelGroup{if $group->cssClassName} {$group->cssClassName}{/if}"><a href="{link controller='LabelGroupEdit' object=$group}{/link}">{$group->groupName}</a>{else}{$group->groupName}</p>{/if}</td>
						
						{event name='columns'}
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
	
	<div class="contentNavigation">
		{@$pagesLinks}
		
		{hascontent}
			<nav>
				<ul>
					{content}
						{if $__wcf->session->getPermission('admin.content.label.canAddLabelGroup')}
							<li><a href="{link controller='LabelGroupAdd'}{/link}" title="{lang}wcf.acp.label.group.add{/lang}" class="button"><span class="icon icon16 icon-plus"></span> <span>{lang}wcf.acp.label.group.add{/lang}</span></a></li>
						{/if}
						
						{event name='contentNavigationButtonsBottom'}
					{/content}
				</ul>
			</nav>
		{/hascontent}
	</div>
{else}
	<p class="info">{lang}wcf.acp.label.group.noneAvailable{/lang}</p>
{/if}

{include file='footer'}
