{include file='header'}
<script type="text/javascript" src="{@$__wcf->getPath()}js/WCF.Label.js"></script>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		WCF.Language.addObject({
			'wcf.acp.label.defaultValue': '{lang}wcf.acp.label.defaultValue{/lang}'
		});
		
		new WCF.Label.ACPList();
	});
	//]]>
</script>

<header class="box48 boxHeadline">
	<img src="{@$__wcf->getPath()}icon/{$action}1.svg" alt="" class="icon48" />
	<hgroup>
		<h1>{lang}wcf.acp.label.{$action}{/lang}</h1>
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
			<li><a href="{link controller='LabelList'}{/link}" title="{lang}wcf.acp.menu.link.label.list{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/label1.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.menu.link.label.list{/lang}</span></a></li>
		</ul>
	</nav>
</div>

{if $labelGroupList|count}
	<form method="post" action="{if $action == 'add'}{link controller='LabelAdd'}{/link}{else}{link controller='LabelEdit'}{/link}{/if}">
		<div class="container containerPadding marginTop shadow">
			<fieldset>
				<legend>{lang}wcf.acp.label.data{/lang}</legend>
				
				<dl{if $errorField == 'groupID'} class="formError"{/if}>
					<dt><label for="groupID">{lang}wcf.acp.label.group{/lang}</label></dt>
					<dd>
						<select id="groupID" name="groupID">
							<option value="0"></option>
							{foreach from=$labelGroupList item=group}
								<option value="{$group->groupID}"{if $group->groupID == $groupID} selected="selected"{/if}>{$group->groupName}</option>
							{/foreach}
						</select>
						{if $errorField == 'groupID'}
							<small class="innerError">
								{if $errorType == 'empty'}
									{lang}wcf.global.form.error.empty{/lang}
								{else}
									{lang}wcf.acp.label.group.error.{@$errorType}{/lang}
								{/if}
							</small>
						{/if}
					</dd>
				</dl>
				
				<dl{if $errorField == 'label'} class="formError"{/if}>
					<dt><label for="label">{lang}wcf.acp.label.label{/lang}</label></dt>
					<dd>
						<input type="text" id="label" name="label" value="{$label}" autofocus="autofocus" class="long" />
						{if $errorField == 'label'}
							<small class="innerError">
								{if $errorType == 'empty'}
									{lang}wcf.global.form.error.empty{/lang}
								{else}
									{lang}wcf.acp.label.label.error.{@$errorType}{/lang}
								{/if}
							</small>
						{/if}
					</dd>
				</dl>
				
				{include file='multipleLanguageInputJavascript' elementIdentifier='label'}
				
				<dl{if $errorField == 'cssClassName'} class="formError"{/if}>
					<dt><label for="cssClassName">{lang}wcf.acp.label.cssClassName{/lang}</label></dt>
					<dd>
						<ul id="labelList">
							{foreach from=$availableCssClassNames item=className}
								{if $className == 'custom'}
									<li class="labelCustomClass"><label><input type="radio" name="cssClassName" value="custom"{if $cssClassName == 'custom'} checked="checked"{/if} /> <span><input type="text" id="customCssClassName" name="customCssClassName" value="{$customCssClassName}" class="long" /></span></label></li>
								{else}
									<li><label><input type="radio" name="cssClassName" value="{$className}"{if $cssClassName == $className} checked="checked"{/if} /> <span class="badge label{if $className != 'none'} {$className}{/if}">Label</span></label></li>
								{/if}
							{/foreach}
						</ul>
						
						{if $errorField == 'cssClassName'}
							<small class="innerError">
								{lang}wcf.acp.label.label.error.{@$errorType}{/lang}
							</small>
						{/if}
					</dd>
				</dl>
			</fieldset>
		</div>
		
		<div class="formSubmit">
			<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
			{if $labelID|isset}<input type="hidden" name="id" value="{@$labelID}" />{/if}
		</div>
	</form>
{else}
	<p class="error">{lang}wcf.acp.label.error.noGroups{/lang}</p>
{/if}

{include file='footer'}
