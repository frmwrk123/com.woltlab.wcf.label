{include file='header'}

<header class="mainHeading">
	<img src="{@RELATIVE_WCF_DIR}icon/{$action}1.svg" alt="" />
	<hgroup>
		<h1>{lang}wcf.acp.label.{$action}{/lang}</h1>
		<h2>{lang}wcf.acp.label.subtitle{/lang}</h2>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.global.form.{$action}.success{/lang}</p>	
{/if}

<div class="contentHeader">
	<nav>
		<ul class="largeButtons">
			<li><a href="{link controller='LabelList'}{/link}" title="{lang}wcf.acp.menu.link.label.list{/lang}" class="button"><img src="{@RELATIVE_WCF_DIR}icon/label1.svg" alt="" /> <span>{lang}wcf.acp.menu.link.label.list{/lang}</span></a></li>
		</ul>
	</nav>
</div>

{if $labelGroupList|count}
	<form method="post" action="{if $action == 'add'}{link controller='LabelAdd'}{/link}{else}{link controller='LabelEdit'}{/link}{/if}">
		<div class="border content">
			<fieldset>
				<legend>{lang}wcf.acp.label.data{/lang}</legend>
				
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
						<input type="text" id="cssClassName" name="cssClassName" value="{$cssClassName}" class="long" />
						{if $errorField == 'cssClassName'}
							<small class="innerError">
								{lang}wcf.acp.label.label.error.{@$errorType}{/lang}
							</small>
						{/if}
					</dd>
				</dl>
				
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
			</fieldset>
		</div>
		
		<div class="formSubmit">
			<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
			<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
			{@SID_INPUT_TAG}
	 		{if $labelID|isset}<input type="hidden" name="id" value="{@$labelID}" />{/if}
		</div>
	</form>
{else}
	<p class="error">{lang}wcf.label.label.error.noGroups{/lang}</p>
{/if}

{include file='footer'}
