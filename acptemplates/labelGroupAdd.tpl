{include file='header'}

<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/WCF.ACL.js"></script>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		new WCF.ACL.List($('#groupPermissions'), 7 /* DEBUG ONLY! */);
	});
	//]]>
</script>

<header class="mainHeading">
	<img src="{@RELATIVE_WCF_DIR}icon/{$action}1.svg" alt="" />
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

<div class="contentHeader">
	<nav>
		<ul class="largeButtons">
			<li><a href="{link controller='LabelGroupList'}{/link}" title="{lang}wcf.acp.menu.link.label.group.list{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/label1.svg" alt="" /> <span>{lang}wcf.acp.menu.link.label.group.list{/lang}</span></a></li>
		</ul>
	</nav>
</div>

<form method="post" action="{if $action == 'add'}{link controller='LabelGroupAdd'}{/link}{else}{link controller='LabelGroupEdit'}{/link}{/if}">
	<div class="border content">
		<fieldset>
			<legend>{lang}wcf.acp.label.group.data{/lang}</legend>
			
			<dl{if $errorField == 'groupName'} class="formError"{/if}>
				<dt><label for="groupName">{lang}wcf.acp.label.group.groupName{/lang}</label></dt>
				<dd>
					<input type="text" id="groupName" name="groupName" value="{$groupName}" class="long" />
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
	
	<div class="formSubmit">
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SID_INPUT_TAG}
 		{if $groupID|isset}<input type="hidden" name="id" value="{@$groupID}" />{/if}
	</div>
</form>

{include file='footer'}
