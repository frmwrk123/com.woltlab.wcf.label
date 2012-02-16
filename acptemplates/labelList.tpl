{include file='header'}

<header class="wcf-mainHeading">
	<img src="{@RELATIVE_WCF_DIR}icon/label1.svg" alt="" />
	<hgroup>
		<h1>{lang}wcf.acp.label.list{/lang}</h1>
		<h2>{lang}wcf.acp.label.subtitle{/lang}</h2>
	</hgroup>
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.Action.Delete('wcf\\data\\label\\LabelAction', $('.jsLabelRow'));
		});
		//]]>
	</script>
</header>

<div class="wcf-contentHeader">
	{pages print=true assign=pagesLinks controller="LabelList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
	
	{if $__wcf->session->getPermission('admin.content.label.canAddLabel')}
		<nav>
			<ul class="wcf-largeButtons">
				<li><a href="{link controller='LabelAdd'}{/link}" title="{lang}wcf.acp.label.add{/lang}" class="wcf-button"><img src="{@RELATIVE_WCF_DIR}icon/add1.svg" alt="" /> <span>{lang}wcf.acp.label.add{/lang}</span></a></li>
			</ul>
		</nav>
	{/if}
</div>

{hascontent}
	<div class="wcf-border wcf-boxTitle">
		<hgroup>
			<h1>{lang}wcf.acp.label.list{/lang} <span class="badge" title="{lang}wcf.acp.label.list.count{/lang}">{#$items}</span></h1>
		</hgroup>
		
		<table class="wcf-table">
			<thead>
				<tr>
					<th class="columnID columnLabelID{if $sortField == 'labelID'} active{/if}" colspan="2"><a href="{link controller='LabelList'}pageNo={@$pageNo}&sortField=labelID&sortOrder={if $sortField == 'labelID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}{if $sortField == 'labelID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnTitle columnLabel{if $sortField == 'label'} active{/if}"><a href="{link controller='LabelList'}pageNo={@$pageNo}&sortField=label&sortOrder={if $sortField == 'label' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.label.label{/lang}{if $sortField == 'label'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnText columnGroup{if $sortField == 'groupName'} active{/if}"><a href="{link controller='LabelList'}pageNo={@$pageNo}&sortField=groupName&sortOrder={if $sortField == 'groupName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.label.group.groupName{/lang}{if $sortField == 'groupName'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					
					{event name='headColumns'}
				</tr>
			</thead>
			
			<tbody>
				{content}
					{foreach from=$objects item=label}
						<tr class="jsLabelRow">
							<td class="columnIcon">
								{if $label->isEditable()}
									<a href="{link controller='LabelEdit' id=$label->labelID}{/link}"><img src="{@RELATIVE_WCF_DIR}icon/edit1.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/edit1D.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" />
								{/if}
								{if $label->isDeletable()}
									<img src="{@RELATIVE_WCF_DIR}icon/delete1.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="jsDeleteButton jsTooltip" data-object-id="{@$label->labelID}" data-confirm-message="{lang}wcf.acp.label.delete.sure{/lang}" />
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/delete1D.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" />
								{/if}

								{event name='buttons'}
							</td>
							<td class="columnID"><p>{@$label->labelID}</p></td>
							<td class="columnTitle columnLabel">{if $label->isEditable()}<p class="label{if $label->cssClassName} {$label->cssClassName}{/if}"><a href="{link controller='LabelEdit' id=$label->labelID}{/link}" title="{lang}{$label->label}{/lang}">{lang}{$label->label}{/lang}</a>{else}{lang}{$label->label}{/lang}</p>{/if}</td>
							<td class="columnText columnGroup"><p>{$label->groupName}</p></td>
							
							{event name='columns'}
						</tr>
					{/foreach}
				{/content}
			</tbody>
		</table>
		
	</div>
	
	<div class="wcf-contentFooter">
		{@$pagesLinks}
		
		{if $__wcf->session->getPermission('admin.content.label.canAddLabel')}
			<nav>
				<ul class="wcf-largeButtons">
					<li><a href="{link controller='LabelAdd'}{/link}" title="{lang}wcf.acp.label.add{/lang}" class="wcf-button"><img src="{@RELATIVE_WCF_DIR}icon/add1.svg" alt="" /> <span>{lang}wcf.acp.label.add{/lang}</span></a></li>
				</ul>
			</nav>
		{/if}
	</div>
{hascontentelse}
	<p class="wcf-warning">{lang}wcf.acp.label.noneAvailable{/lang}</p>
{/hascontent}

{include file='footer'}
