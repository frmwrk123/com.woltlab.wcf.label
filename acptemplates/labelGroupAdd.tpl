{include file='header'}

{include file='aclPermissions' sandbox=false}
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		new WCF.ACL.List($('#groupPermissions'), {@$objectTypeID}{if $groupID|isset}, '', {@$groupID}{/if});
		
		WCF.TabMenu.init();
	});
	//]]>
</script>

<header class="wcf-container wcf-mainHeading">
	<img src="{@$__wcf->getPath()}icon/{$action}1.svg" alt="" class="wcf-containerIcon" />
	<hgroup class="wcf-containerContent">
		<h1>{lang}wcf.acp.label.group.{$action}{/lang}</h1>
		<h2>{lang}wcf.acp.label.group.subtitle{/lang}</h2>
	</hgroup>
</header>

{if $errorField}
	<p class="wcf-error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="wcf-success">{lang}wcf.global.form.{$action}.success{/lang}</p>	
{/if}

<div class="wcf-contentHeader">
	<nav>
		<ul class="wcf-largeButtons">
			<li><a href="{link controller='LabelGroupList'}{/link}" title="{lang}wcf.acp.menu.link.label.group.list{/lang}" class="wcf-button"><img src="{@$__wcf->getPath()}icon/label1.svg" alt="" /> <span>{lang}wcf.acp.menu.link.label.group.list{/lang}</span></a></li>
		</ul>
	</nav>
</div>

<form method="post" action="{if $action == 'add'}{link controller='LabelGroupAdd'}{/link}{else}{link controller='LabelGroupEdit'}{/link}{/if}">
	<div class="wcf-tabMenuContainer">
		<nav class="wcf-tabMenu">
			<ul>
				<li><a href="#general">{lang}wcf.acp.label.group.category.general{/lang}</a></li>
				<li><a href="#connect">{lang}wcf.acp.label.group.category.connect{/lang}</a></li>
			</ul>
		</nav>
		
		<div id="general" class="wcf-box wcf-boxPadding wcf-tabMenuContainer wcf-tabMenuContent">
			<hgroup class="wcf-subHeading">
				<h1>{lang}wcf.acp.label.group.category.general{/lang}</h1>
			</hgroup>
			
			<fieldset>
				<legend>{lang}wcf.acp.label.group.data{/lang}</legend>
				
				<dl{if $errorField == 'groupName'} class="wcf-formError"{/if}>
					<dt><label for="groupName">{lang}wcf.acp.label.group.groupName{/lang}</label></dt>
					<dd>
						<input type="text" id="groupName" name="groupName" value="{$groupName}" autofocus="autofocus" class="long" />
						{if $errorField == 'groupName'}
							<small class="wcf-innerError">
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
		
		<div id="connect" class="wcf-box wcf-boxPadding wcf-tabMenuContainer wcf-tabMenuContent">
			<hgroup class="wcf-subHeading">
				<h1>{lang}wcf.acp.label.group.category.connect{/lang}</h1>
			</hgroup>
			
			<style type="text/css">
				#test li {
					background-color: rgba(224, 224, 224, .3);
					padding: 3px 40px 3px 3px;
					text-align: right;
				}
				
				#test li.category {
					background-color: rgba(192, 192, 192, .3);
				}
				
				#test li span:first-child {
					float: left;
				}
			</style>
			
			{foreach from=$labelObjectTypeContainers item=container}
				{if $container->isBooleanOption()}
					<!-- TODO: Implement boolean option mode -->
				{else}
					<dl>
						<dt>objectTypeID = {@$container->getObjectTypeID()}</dt>
						<dd>
							<ul id="test" class="wcf-box wcf-boxPadding">
								{foreach from=$container item=objectType}
									<li class="{if $objectType->isCategory()} category{/if}"{if $objectType->getDepth()} style="padding-left: {40 * $objectType->getDepth()}px"{/if}>
										<label for="checkbox_{@$container->getObjectTypeID()}_{@$objectType->getObjectID()}">
											<span>{$objectType->getLabel()}</span>
											<span><input id="checkbox_{@$container->getObjectTypeID()}_{@$objectType->getObjectID()}" type="checkbox" name="objectTypes[{@$container->getObjectTypeID()}][]" value="{@$objectType->getObjectID()}" /></span>
										</label>
									</li>
								{/foreach}
							</ul>
						</dd>
					</dl>
				{/if}
			{/foreach}
		</div>
	</div>
	
	<div class="wcf-formSubmit">
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SID_INPUT_TAG}
 		{if $groupID|isset}<input type="hidden" name="id" value="{@$groupID}" />{/if}
	</div>
</form>

{include file='footer'}
