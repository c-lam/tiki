{if $prefs.controls_style eq 'tab'}
	{include file="tiki-pagecontrols-tab-header.tpl"}
{elseif $prefs.controls_style eq 'classic'}
	{include file="tiki-pagecontrols-classic-header.tpl"}
{else}
	{* Just a fall-back default in case there is a problem *}
	{include file="tiki-pagecontrols-tab-header.tpl"}
{/if}