{* $Id: tiki-adminusers.tpl 14918 2008-09-30 15:28:29Z ricks99 $ *}
{title admpage="general"}{tr}Log SQL{/tr}{/title}

{if $prefs.log_sql ne 'y'}
	{remarksbox type="warning" title="{tr}Notice{/tr}"}}{tr}This feature is disabled{/tr}<br />{tr}You will not see the latest queries.{/tr}{/remarksbox}
{/if}

<div class="navbar">
	{button href="?clean=y" _text="{tr}Clean{/tr}"}
</div>

{include file='find.tpl' find_show_num_rows='y'}

<table class="normal">
	<tr>
		<th>{self_link _sort_arg='sort_mode' _sort_field='created'}{tr}Created{/tr}{/self_link}</th>
		<th>{self_link _sort_arg='sort_mode' _sort_field='sql1'}{tr}Query{/tr}{/self_link}</th>
		<th>{self_link _sort_arg='sort_mode' _sort_field='params'}{tr}Params{/tr}{/self_link}</th>
		<th>{self_link _sort_arg='sort_mode' _sort_field='tracer'}{tr}From{/tr}{/self_link}</th>
		<th>{self_link _sort_arg='sort_mode' _sort_field='timer'}{tr}Time{/tr}{/self_link}</th>
	<tr>
	{foreach from=$logs item=log}
		<tr>
			<td>{$log.created|escape}</td>
			<td>{$log.sql1|escape}</td>
			<td>{$log.params|escape}</td>
			<td>{$log.tracer|escape}</td>
			<td>{$log.timer|escape}</td>
		</tr>
	{/foreach}
</table>
{pagination_links cant=$cant step=$numrows offset=$offset}{/pagination_links}