{title help="User+Calendar" url="tiki-minical.php?view=$view"}{tr}Mini Calendar{/tr}{/title}

{if $prefs.feature_ajax ne 'y' && $prefs.feature_mootools ne 'y'}
  {include file=tiki-mytiki_bar.tpl}
{/if}

{if $prefs.feature_ajax ne 'y' && $prefs.feature_mootools ne 'y'}
	{include file=tiki-mytiki_bar.tpl}
{/if}

<div class="navbar">
	{button href="tiki-minical.php#add" _text="{tr}Add{/tr} "}
	{button href="tiki-minical_prefs.php" _text="{tr}Prefs{/tr}"}
	{button href="tiki-minical.php?view=daily" _text="{tr}Daily{/tr}"}
	{button href="tiki-minical.php?view=weekly" _text="{tr}Weekly{/tr}"}
	{button href="tiki-minical.php?view=list" _text="{tr}List{/tr}"}
	{button href="tiki-minical_export.php" _text="{tr}Export{/tr}"}
	{button href="tiki-minical_prefs.php#import" _text="{tr}Import{/tr}"}
</div>

<table class="normal" >
<tr>
	<td class="formcolor">
<b>{tr}Upcoming events{/tr}</b><br />
<table class="normal">
{section name=ix loop=$upcoming}
<tr>
	<td class="even">
{$upcoming[ix].start|tiki_date_format:"%b %d %H:%M"}
		{if $upcoming[ix].topicId}
			{if $upcoming[ix].topic.isIcon eq 'y'}
			<img title="{$upcoming[ix].topic.name}" src="{$upcoming[ix].topic.path}" alt="{tr}topic image{/tr}" />
			{else}
			<img title="{$upcoming[ix].topic.name}" src="tiki-view_minical_topic?topicId={$upcoming[ix].topicId}" alt="{tr}topic image{/tr}" />
			{/if}
    	{/if}
<a title="{$upcoming[ix].start|tiki_short_time}-{$upcoming[ix].end|tiki_short_time}:{$upcoming[ix].description}" class="link" href="tiki-minical.php?view={$view}&amp;eventId={$upcoming[ix].eventId}#add">{$upcoming[ix].title}</a>
</td>
</tr>
{sectionelse}
<tr><td class="odd">{tr}No records found.{/tr}</td></tr>
{/section}	
</table>
	</td>
	<td width="180" class="formcolor">
		{include file="modules/mod-calendar.tpl"}
	</td>
</tr>
</table>
<br />
<!-- Time here -->
<!--[{$pdate_h|tiki_date_format:"%H"}:{$pdate_h|tiki_date_format:"%M"}]-->

{cycle values="odd,even" print=false}
{if $view eq 'daily'}
<b><a class="link" href="tiki-minical.php?view={$view}&amp;day={$yesterday|tiki_date_format:"%d"}&amp;mon={$yesterday|tiki_date_format:"%m"}&amp;year={$yesterday|tiki_date_format:"%Y"}">{icon _id='resultset_previous' style="vertical-align:middle"}</a>
{$pdate|tiki_long_date} 
<a class="link" href="tiki-minical.php?view={$view}&amp;day={$tomorrow|tiki_date_format:"%d"}&amp;mon={$tomorrow|tiki_date_format:"%m"}&amp;year={$tomorrow|tiki_date_format:"%Y"}">{icon _id='resultset_next' style="vertical-align:middle"}</a>
</b>
<table clas="normal" width="100%">
{section name=ix loop=$slots}
<tr>
	<td class="{cycle}">
	    <table>
	    <tr>
	    <td>
    	{$slots[ix].start|tiki_short_time}<!--<br />{$slots[ix].end|tiki_short_time}-->
    	</td>
    	<td>
    	{section name=jj loop=$slots[ix].events}
    	{if $slots[ix].events[jj].topicId}
			{if $slots[ix].events[jj].topic.isIcon eq 'y'}
			<img title="{$slots[ix].events[jj].topic.name}" src="{$slots[ix].events[jj].topic.path}" alt="{tr}topic image{/tr}" />
			{else}
			<img title="{$slots[ix].events[jj].topic.name}" src="tiki-view_minical_topic?topicId={$slots[ix].events[jj].topicId}" alt="{tr}topic image{/tr}" />
			{/if}
    	{/if}
    	
    	<a title="{$slots[ix].events[jj].start|tiki_short_time}-{$slots[ix].events[jj].end|tiki_short_time}:{$slots[ix].events[jj].description}" class="link" href="tiki-minical.php?view={$view}&amp;eventId={$slots[ix].events[jj].eventId}#add">{$slots[ix].events[jj].title}</a>
    	<a class="link" href="tiki-minical.php?view={$view}&amp;remove={$slots[ix].events[jj].eventId}">{icon _id='cross' alt='{tr}Remove{/tr}' style="vertical-align:middle;"}</a>
    	<br />
    	{/section}
    	</td>
    	</tr>
    	</table>
	</td>
</tr>
{/section}
</table>
{/if}

{if $view eq 'weekly'}
<a class="link" href="tiki-minical.php?view={$view}&amp;day={$prev_week_start|tiki_date_format:"%d"}&amp;mon={$prev_week_start|tiki_date_format:"%m"}&amp;year={$prev_week_start|tiki_date_format:"%Y"}">{icon _id='resultset_previous'}</a>
<b>{$week_start|tiki_date_format:"%b"} {$week_start|tiki_date_format:"%d"}-{$week_end|tiki_date_format:"%b"} {$week_end|tiki_date_format:"%d"}</b>
<a class="link" href="tiki-minical.php?view={$view}&amp;day={$next_week_start|tiki_date_format:"%d"}&amp;mon={$next_week_start|tiki_date_format:"%m"}&amp;year={$next_week_start|tiki_date_format:"%Y"}">{icon _id='resultset_next'}</a>
<table class="normal"  >
{section name=ix loop=$slots}
<tr>
	<td class="{cycle}">
	    <table >
	    <tr>
	    <td >
    	<a class="link" href="tiki-minical.php?view=daily&amp;day={$slots[ix].start|tiki_date_format:"%d"}&amp;mon={$slots[ix].start|tiki_date_format:"%m"}&amp;year={$slots[ix].start|tiki_date_format:"%Y"}">{$slots[ix].start|tiki_date_format:"%a"}<br />
    	{$slots[ix].start|tiki_date_format:"%d"}</a>
    	</td>
    	<td>
    	{section name=jj loop=$slots[ix].events}
    	{$slots[ix].events[jj].start|tiki_short_time}: 
    	{if $slots[ix].events[jj].topicId}
			{if $slots[ix].events[jj].topic.isIcon eq 'y'}
			<img title="{$slots[ix].events[jj].topic.name}" src="{$slots[ix].events[jj].topic.path}" alt="{tr}topic image{/tr}" />
			{else}
			<img title="{$slots[ix].events[jj].topic.name}" src="tiki-view_minical_topic?topicId={$slots[ix].events[jj].topicId}" alt="{tr}topic image{/tr}" />
			{/if}
    	{/if}

    	<a title="{$slots[ix].events[jj].start|tiki_short_time}:{$slots[ix].events[jj].description}" class="link" href="tiki-minical.php?view={$view}&amp;eventId={$slots[ix].events[jj].eventId}#add">{$slots[ix].events[jj].title}</a>
    	[<a class="link" href="tiki-minical.php?view={$view}&amp;remove={$slots[ix].events[jj].eventId}">x</a>]
    	<br />
    	{/section}
    	</td>
    	</tr>
    	</table>
	</td>
</tr>
{/section}
</table>
{/if}

{if $view eq 'list' and (count($channels) > 0 or $find ne '')}

{include file='find.tpl'}

<a class="link" href="tiki-minical.php?view={$view}&amp;removeold=1">{tr}Remove old events{/tr}</a>
<form action="tiki-minical.php" method="post">
<input type="hidden" name="view" value="{$view|escape}" />
<table class="normal">
<tr>
<th><input type="submit" name="delete" value="x " /></th>
<th><a href="tiki-minical.php?view={$view}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'title_desc'}title_asc{else}title_desc{/if}">{tr}Title{/tr}</a></th>
<th><a href="tiki-minical.php?view={$view}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'start_desc'}start{else}start_desc{/if}">{tr}Start{/tr}</a></th>
<th><a href="tiki-minical.php?view={$view}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'duration_desc'}duration_asc{else}duration_desc{/if}">{tr}duration{/tr}</a></th>
<th style="text-align:center;"><a href="tiki-minical.php?view={$view}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'topicId_desc'}topicId_asc{else}topicId_desc{/if}">{tr}Topic{/tr}</a></th>
</tr>
{cycle values="odd,even" print=false}
{section name=user loop=$channels}
<tr>
<td style="text-align:center;" class="{cycle advance=false}">
<input type="checkbox" name="event[{$channels[user].eventId}]" />
</td>
<td class="{cycle advance=false}"><a class="link" href="tiki-minical.php?view={$view}&amp;eventId={$channels[user].eventId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;find={$find}#add">{$channels[user].title}</a></td>
<td class="{cycle advance=false}">{$channels[user].start|tiki_short_datetime}</td>
<td class="{cycle advance=false}">{math equation="x / 3600" x=$channels[user].duration format="%d"} {tr}h{/tr} {math equation="(x % 3600) / 60" x=$channels[user].duration} {tr}mins{/tr}</td>
<td style="text-align:center;" class="{cycle advance=false}">
    	{if $channels[user].topicId}
			{if $channels[user].topic.isIcon eq 'y'}
			<img title="{$channels[user].topic.name}" src="{$channels[user].topic.path}" alt="{tr}topic image{/tr}" />
			{else}
			<img title="{$channels[user].topic.name}" src="tiki-view_minical_topic?topicId={$channels[user].topicId}" alt="{tr}topic image{/tr}" />
			{/if}
    	{/if}

</td>
</tr>
{/section}
</table>
</form>

{pagination_links cant=$cant_pages step=$prefs.maxRecords offset=$offset}{/pagination_links}

{/if}
<a name="add"></a>
<h2>{if $eventId}{tr}Edit event{/tr}{else}{tr}Add event{/tr}{/if}</h2>
<form action="tiki-minical.php" method="post">
<input type="hidden" name="eventId" value="{$eventId|escape}" />
<input type="hidden" name="view" value="{$view|escape}" />
<input type="hidden" name="duration" value="60*60" />
<table class="normal">
  <tr><td class="formcolor">{tr}Title{/tr}</td>
      <td class="formcolor"><input type="text" name="title" value="{$info.title|escape}" style="width:95%"/>
      </td>
  </tr>
  <tr>
  	  <td class="formcolor">{tr}Start{/tr}</td>
  	  <td class="formcolor">
  	  {html_select_date time=$ev_pdate end_year="+4" field_order=$prefs.display_field_order} {tr}at{/tr}
  	  {html_select_time minute_interval=5 time=$ev_pdate_h display_seconds=false use_24_hours=true}
  	  </td>
  </tr>
  <tr>
  	  <td class="formcolor">{tr}Duration{/tr}</td>
  	  <td class="formcolor">
	  <select name="duration_hours">
	  {html_options output=$hours values=$hours selected=$duration_hours}
	  </select> {if $duration_hours>1}{tr}hours{/tr}{else}{tr}hour{/tr}{/if}
 	  <select name="duration_minutes">
	  {html_options output=$minutes values=$minutes selected=$duration_minutes}
	  </select> {tr}minutes{/tr}

  	  </td>
  	  
  </tr>
  <tr>
  	  <td class="formcolor">{tr}Topic{/tr}</td>
  	  <td class="formcolor">
  	  <select name="topicId">
  	  <option value="0" {if $info.topicId eq $topics[ix].topicId}selected="selected"{/if}>{tr}None{/tr}</option>
  	  {section name=ix loop=$topics}
  	  <option value="{$topics[ix].topicId|escape}" {if $info.topicId eq $topics[ix].topicId}selected="selected"{/if}>{$topics[ix].name}</option>
  	  {/section}
  	  </select>
  	  </td>
  </tr>
  <tr>
  	  <td class="formcolor">{tr}Description{/tr}</td>
  	  <td class="formcolor">
  	  <textarea name="description" rows="5" cols="80" style="width:95%">{$info.description|escape}</textarea>
      </td>
  </tr>
  <tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" name="save" value="{tr}Save{/tr}" />
{if $eventId}		<input type="submit" name="remove2" value="{tr}Delete{/tr}" />
{/if}
	</td>
  </tr>
</table>
</form>