{include file='header'}

{include file='aclPermissions'}
<script type="text/javascript" src="{@$__wcf->getPath()}js/WCF.Label.js"></script>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		new WCF.ACL.List($('#groupPermissions'), {@$objectTypeID}{if $groupID|isset}, '', {@$groupID}{/if});
		new WCF.Label.ACPList.Connect();
		
		WCF.TabMenu.init();
	});
	//]]>
</script>

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.label.group.{$action}{/lang}</h1>
		<h2>{lang}wcf.acp.label.group.subtitle{/lang}</h2>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.global.form.{$action}.success{/lang}</p>	
{/if}

<div class="contentNavigation">
	<nav>
		<ul>
			<li><a href="{link controller='LabelGroupList'}{/link}" title="{lang}wcf.acp.menu.link.label.group.list{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/list.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.menu.link.label.group.list{/lang}</span></a></li>
		</ul>
	</nav>
</div>

<form method="post" action="{if $action == 'add'}{link controller='LabelGroupAdd'}{/link}{else}{link controller='LabelGroupEdit'}{/link}{/if}">
	<div class="tabMenuContainer">
		<nav class="tabMenu">
			<ul>
				<li><a href="#general">{lang}wcf.acp.label.group.category.general{/lang}</a></li>
				<li><a href="#connect">{lang}wcf.acp.label.group.category.connect{/lang}</a></li>
			</ul>
		</nav>
		
		<div id="general" class="container containerPadding tabMenuContainer tabMenuContent">
			<fieldset>
				<legend>{lang}wcf.acp.label.group.data{/lang}</legend>
				
				<dl{if $errorField == 'groupName'} class="formError"{/if}>
					<dt><label for="groupName">{lang}wcf.acp.label.group.groupName{/lang}</label></dt>
					<dd>
						<input type="text" id="groupName" name="groupName" value="{$groupName}" autofocus="autofocus" class="long" />
						{if $errorField == 'groupName'}
							<small class="innerError">
								{if $errorType == 'empty'}
									{lang}wcf.global.form.error.empty{/lang}
								{else}
									{lang}wcf.acp.label.group.groupName.error.{@$errorType}{/lang}
								{/if}
							</small>
						{/if}
					</dd>
				</dl>
	
				<dl id="groupPermissions">
					<dt>{lang}wcf.acp.acl.permissions{/lang}</dt>
					<dd></dd>
				</dl>
			</fieldset>
		</div>
		
		<div id="connect" class="container containerPadding tabMenuContainer tabMenuContent">
			<fieldset>
				<legend>{lang}wcf.acp.label.group.category.connect{/lang}</legend>
			
				{foreach from=$labelObjectTypeContainers item=container}
					{if $container->isBooleanOption()}
						<!-- TODO: Implement boolean option mode -->
					{else}
						<dl>
							<dt>objectTypeID = {@$container->getObjectTypeID()}</dt>
							<dd>
								<ul class="container structuredList">
									{foreach from=$container item=objectType}
										<li class="{if $objectType->isCategory()} category{/if}"{if $objectType->getDepth()} style="padding-left: {21 * $objectType->getDepth()}px"{/if} data-depth="{@$objectType->getDepth()}">
											<span>{$objectType->getLabel()}</span>
											<label><input id="checkbox_{@$container->getObjectTypeID()}_{@$objectType->getObjectID()}" type="checkbox" name="objectTypes[{@$container->getObjectTypeID()}][]" value="{@$objectType->getObjectID()}"{if $objectType->getOptionValue()} checked="checked"{/if} /></label>
										</li>
									{/foreach}
								</ul>
							</dd>
						</dl>
					{/if}
				{/foreach}
			</fieldset>
		</div>
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{if $groupID|isset}<input type="hidden" name="id" value="{@$groupID}" />{/if}
	</div>
</form>

{include file='footer'}
