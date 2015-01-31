{* $Id: layout_view.tpl 48366 2013-11-08 16:12:24Z lphuberdeau $ *}<!DOCTYPE html>
<html lang="{if !empty($pageLang)}{$pageLang}{else}{$prefs.language}{/if}"{if !empty($page_id)} id="page_{$page_id}"{/if}>
	<head>
		{include file='header.tpl'}
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body{html_body_attributes class="navbar-padding"}>
		{$cookie_consent_html}

		{if $prefs.feature_ajax eq 'y'}
			{include file='tiki-ajax_header.tpl'}
		{/if}
        <div class="middle_outer">
            <div class="fixed-topbar"> </div>
		<div class="container clearfix middle" id="middle">
            <div class="topbar_wrapper">
                <div class="container">
    				<div id="tiki-top" class="topbar">
	    				<div class="row">
		    				<div class="col-md-12">
			    				{modulelist zone=topbar}
                            </div>
						</div>
					</div>
				</div>
            </div>

		<div class="row">
				{if zone_is_empty('left') and zone_is_empty('right')}
					<div class="col-md-12" id="col1">
						<div class="pull-right">{block name=quicknav}{/block}</div>
						{block name=title}{/block}
						{block name=navigation}{/block}
						{error_report}
						{block name=content}{/block}
						{if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
							{modulelist zone=pagebottom}
						{/if}
					</div>
				{elseif zone_is_empty('left')}
					<div class="col-md-9" id="col1">
						<div class="pull-right">{block name=quicknav}{/block}</div>
						{block name=title}{/block}
						{block name=navigation}{/block}
						{error_report}
						{block name=content}{/block}
						{if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
							{modulelist zone=pagebottom}
						{/if}
					</div>
					<div class="col-md-3" id="col3">
						{modulelist zone=right}
					</div>
				{elseif zone_is_empty('right')}
					<div class="col-md-9 col-md-push-3" id="col1">
						<div class="pull-right">{block name=quicknav}{/block}</div>
						{block name=title}{/block}
						{block name=navigation}{/block}
						{error_report}
						{block name=content}{/block}
						{if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
							{modulelist zone=pagebottom}
						{/if}
					</div>
					<div class="col-md-3 col-md-pull-9" id="col2">
						{modulelist zone=left}
					</div>
				{else}
					<div class="col-md-8 col-md-push-2" id="col1">
						<div class="pull-right">{block name=quicknav}{/block}</div>
						{block name=title}{/block}
						{block name=navigation}{/block}
						{error_report}
						{block name=content}{/block}
						{if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
							{modulelist zone=pagebottom}
						{/if}
					</div>
					<div class="col-md-2 col-md-pull-8" id="col2">
						{modulelist zone=left}
					</div>
					<div class="col-md-2" id="col3">
						{modulelist zone=right}
					</div>
				{/if}
            </div></div>
		</div>

		<div class="">
			<footer class="main-footer">
				<div class="container">
					<!-- content modules col-md-3 -->
					{modulelist zone=bottom}
				</div>
			</footer>
		</div>

    <nav class="navbar {* navbar-inverse *}navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                {if $module_params.navbar_toggle neq 'n'}
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-social-modules">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                {/if}
            </div>
		    <div class="collapse navbar-collapse fixed-topbar" id="navbar-collapse-social-modules">
	          {modulelist zone="top" nobox="y" navbar="y" menuclass="navbar-inverse collapse navbar-collapse noclearfix" class="top_modules modules row context modules noclearfix"}
	        </div>
        </div>
    </nav>
		{include file='footer.tpl'}
	</body>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.tooltips').tooltip({
				'container': 'body'
			});
		});
	</script>
</html>
{if !empty($smarty.request.show_smarty_debug)}
	{debug}
{/if}