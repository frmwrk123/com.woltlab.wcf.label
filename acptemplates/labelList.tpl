{include file='header'}

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		new WCF.Action.Delete('wcf\\data\\label\\LabelAction', $('.jsLabelRow'));
	});
	//]]>
</script>

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.label.list{/lang}</h1>
	</hgroup>
</header>

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller="LabelList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
	
	{if $__wcf->session->getPermission('admin.content.label.canAddLabel')}
		<nav>
			<ul>
				<li><a href="{link controller='LabelAdd'}{/link}" title="{lang}wcf.acp.label.add{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/add1.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.label.add{/lang}</span></a></li>
			</ul>
		</nav>
	{/if}
</div>

{hascontent}
	<div class="tabularBox tabularBoxTitle marginTop shadow">
		<hgroup>
			<h1>{lang}wcf.acp.label.list{/lang} <span class="badge badgeInverse" title="{lang}wcf.acp.label.list.count{/lang}">{#$items}</span></h1>
		</hgroup>
		
		<table class="table">
			<thead>
				<tr>
					<th class="columnID columnLabelID{if $sortField == 'labelID'} active{/if}" colspan="2"><a href="{link controller='LabelList'}pageNo={@$pageNo}&sortField=labelID&sortOrder={if $sortField == 'labelID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}{if $sortField == 'labelID'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnTitle columnLabel{if $sortField == 'label'} active{/if}"><a href="{link controller='LabelList'}pageNo={@$pageNo}&sortField=label&sortOrder={if $sortField == 'label' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.label.label{/lang}{if $sortField == 'label'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnText columnGroup{if $sortField == 'groupName'} active{/if}"><a href="{link controller='LabelList'}pageNo={@$pageNo}&sortField=groupName&sortOrder={if $sortField == 'groupName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.label.group.groupName{/lang}{if $sortField == 'groupName'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					
					{event name='headColumns'}
				</tr>
			</thead>
			
			<tbody>
				{content}
					{foreach from=$objects item=label}
						<tr class="jsLabelRow">
							<td class="columnIcon">
								{if $label->isEditable()}
									<a href="{link controller='LabelEdit' id=$label->labelID}{/link}"><img src="{@$__wcf->getPath()}icon/edit1.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16 jsTooltip" /></a>
								{else}
									<img src="{@$__wcf->getPath()}icon/edit1D.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16" />
								{/if}
								{if $label->isDeletable()}
									<img src="{@$__wcf->getPath()}icon/delete1.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16 jsDeleteButton jsTooltip" data-object-id="{@$label->labelID}" data-confirm-message="{lang}wcf.acp.label.delete.sure{/lang}" />
								{else}
									<img src="{@$__wcf->getPath()}icon/delete1D.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16" />
								{/if}

								{event name='buttons'}
							</td>
							<td class="columnID"><p>{@$label->labelID}</p></td>
							<td class="columnTitle columnLabel">{if $label->isEditable()}<p><a href="{link controller='LabelEdit' id=$label->labelID}{/link}" title="{lang}{$label->label}{/lang}" class="badge label{if $label->cssClassName} {$label->cssClassName}{/if}">{lang}{$label->label}{/lang}</a></p>{else}<p" class="badge label{if $label->cssClassName} {$label->cssClassName}{/if}">{lang}{$label->label}{/lang}</p>{/if}</td>
							<td class="columnText columnGroup"><p>{$label->groupName}</p></td>
							
							{event name='columns'}
						</tr>
					{/foreach}
				{/content}
			</tbody>
		</table>
		
	</div>
	
	<div class="contentNavigation">
		{@$pagesLinks}
		
		{if $__wcf->session->getPermission('admin.content.label.canAddLabel')}
			<nav>
				<ul>
					<li><a href="{link controller='LabelAdd'}{/link}" title="{lang}wcf.acp.label.add{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/add1.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.label.add{/lang}</span></a></li>
				</ul>
			</nav>
		{/if}
	</div>
{hascontentelse}
	<p class="info">{lang}wcf.acp.label.noneAvailable{/lang}</p>
{/hascontent}

{include file='footer'}
