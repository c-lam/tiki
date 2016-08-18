{* $Id$ *}

{title help="Hotwords"}{tr}Admin Hotwords{/tr}{/title}

<h2>{tr}Add Hotword{/tr}</h2>

<form method="post" action="tiki-admin_hotwords.php">
<table class="normal">
<tr><td class="formcolor">{tr}Word{/tr}</td><td class="formcolor"><input type="text" name="word" /></td></tr>
<tr><td class="formcolor">{tr}URL{/tr}</td><td class="formcolor"><input type="text" name="url" /></td></tr>
<tr><td class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="add" value="{tr}Add{/tr}" /></td></tr>
</table>
</form>

<h2>{tr}Hotwords{/tr}</h2>
{if $words}
  {include file='find.tpl' _sort_mode='y'}
{/if}
<table class="normal">
<tr>
<th><a href="tiki-admin_hotwords.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'word_desc'}word_asc{else}word_desc{/if}">{tr}Word{/tr}</a></th>
<th><a href="tiki-admin_hotwords.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'url_desc'}url_asc{else}url_desc{/if}">{tr}URL{/tr}</a></th>
<th>{tr}Action{/tr}</th>
</tr>
{cycle values="odd,even" print=false}
{section name=user loop=$words}
<tr>
<td class="{cycle advance=false}">{$words[user].word}</td>
<td class="{cycle advance=false}">{$words[user].url}</td>
<td class="{cycle advance=true}">
<a class="link" href="tiki-admin_hotwords.php?remove={$words[user].word|escape:"url"}{if $offset}&amp;offset={$offset}{/if}&amp;sort_mode={$sort_mode}" 
title="{tr}Delete{/tr}">{icon _id='cross' alt='{tr}Delete{/tr}'}</a>
</td>
</tr>
{sectionelse}
<tr><td colspan="3" class="odd">{tr}No records found{/tr}</td></tr>
{/section}
</table>

{pagination_links cant=$cant_pages step=$prefs.maxRecords offset=$offset }{/pagination_links}