{include file='header'}

<header class="boxHeadline">
	<hgroup class="wcf-containerContent">
		<h1>{lang}wcf.acp.label.group.list{/lang}</h1>
	</hgroup>
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.Action.Delete('wcf\\data\\label\\group\\LabelGroupAction', $('.jsLabelGroupRow'));
		});
		//]]>
	</script>
</header>

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller="LabelGroupList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
	
	{if $__wcf->session->getPermission('admin.content.label.canAddLabelGroup')}
		<nav>
			<ul>
				<li><a href="{link controller='LabelGroupAdd'}{/link}" title="{lang}wcf.acp.label.group.add{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/add1.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.label.group.add{/lang}</span></a></li>
			</ul>
		</nav>
	{/if}
</div>

{hascontent}
	<div class="tabularBox tabularBoxTitle marginTop shadow">
		<hgroup>
			<h1>{lang}wcf.acp.label.group.list{/lang} <span class="badge badgeInverse" title="{lang}wcf.acp.label.group.list.count{/lang}">{#$items}</span></h1>
		</hgroup>
		
		<table class="table">
			<thead>
				<tr>
					<th class="columnID columnLabelGroupID{if $sortField == 'groupID'} active{/if}" colspan="2"><a href="{link controller='LabelGroupList'}pageNo={@$pageNo}&sortField=groupID&sortOrder={if $sortField == 'groupID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}{if $sortField == 'groupID'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnTitle columnGroupName{if $sortField == 'groupName'} active{/if}"><a href="{link controller='LabelGroupList'}pageNo={@$pageNo}&sortField=groupName&sortOrder={if $sortField == 'groupName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.label.group.groupName{/lang}{if $sortField == 'groupName'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					
					{event name='headColumns'}
				</tr>
			</thead>
			
			<tbody>
				{content}
					{foreach from=$objects item=group}
						<tr class="jsLabelGroupRow">
							<td class="columnIcon">
								{if $group->isEditable()}
									<a href="{link controller='LabelGroupEdit' id=$group->groupID}{/link}"><img src="{@$__wcf->getPath()}icon/edit1.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16 jsTooltip" /></a>
								{else}
									<img src="{@$__wcf->getPath()}icon/edit1D.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16" />
								{/if}
								{if $group->isDeletable()}
									<img src="{@$__wcf->getPath()}icon/delete1.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16 jsDeleteButton jsTooltip" data-object-id="{@$group->groupID}" data-confirm-message="{lang}wcf.acp.label.group.delete.sure{/lang}" />
								{else}
									<img src="{@$__wcf->getPath()}icon/delete1D.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16" />
								{/if}

								{event name='buttons'}
							</td>
							<td class="columnID"><p>{@$group->groupID}</p></td>					
							<td class="columnTitle columnGroupName">{if $group->isEditable()}<p class="labelGroup{if $group->cssClassName} {$group->cssClassName}{/if}"><a href="{link controller='LabelGroupEdit' id=$group->groupID}{/link}">{$group->groupName}</a>{else}{$group->groupName}</p>{/if}</td>
							
							{event name='columns'}
						</tr>
					{/foreach}
				{/content}
			</tbody>
		</table>
		
	</div>
	
	<div class="contentNavigation">
		{@$pagesLinks}
		
		{if $__wcf->session->getPermission('admin.content.label.canAddLabelGroup')}
			<nav>
				<ul>
					<li><a href="{link controller='LabelGroupAdd'}{/link}" title="{lang}wcf.acp.label.group.add{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/add1.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.label.group.add{/lang}</span></a></li>
				</ul>
			</nav>
		{/if}
	</div>
{hascontentelse}
	<p class="info">{lang}wcf.acp.label.group.noneAvailable{/lang}</p>
{/hascontent}

{include file='footer'}
