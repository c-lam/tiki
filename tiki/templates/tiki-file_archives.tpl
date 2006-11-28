{* $Header: /cvsroot/tikiwiki/tiki/templates/tiki-file_archives.tpl,v 1.3 2006-11-28 14:54:54 sylvieg Exp $ *}

<h1><a class="pagetitle" href="tiki-file_archives.php?fileId={$file_info.fileId}">{tr}File Archive{/tr}: {if empty($file_info.name)}{$file_info.filename|escape}{else}{$file_info.name}{/if}</a></h1>

<table><tr>
<td style="vertical-align:top;">

<a href="tiki-file_galleries.php" class="linkbut" title="{tr}list galleries{/tr}">{tr}list galleries{/tr}</a>
<a href="tiki-list_file_gallery.php?galleryId={$gal_info.galleryId}" class="linkbut" title="{tr}list gallery{/tr}">{tr}list gallery{/tr}</a>

{if $tiki_p_admin_file_galleries eq 'y' or $user eq $gal_info.user}
  <a href="tiki-file_galleries.php?edit_mode=1&amp;galleryId={$gal_info.galleryId}" class="linkbut" title="{tr}edit gallery{/tr}">{tr}edit gallery{/tr}</a>
{/if}

{if $tiki_p_admin_file_galleries eq 'y' or $user eq $gal_info.user or $gal_info.public eq 'y'}
  {if $tiki_p_upload_files eq 'y'}
    <a href="tiki-upload_file.php?galleryId={$gal_info.galleryId}" class="linkbut">{tr}upload file{/tr}</a>
  {/if}
  {if $feature_file_galleries_batch eq "y" and $tiki_p_batch_upload_file_dir eq 'y'}
    <a href="tiki-batch_upload_files.php?galleryId={$gal_info.galleryId}" class="linkbut">{tr}Directory batch{/tr}</a>
  {/if}
{/if}
</td></tr></table>

{include file=list_file_gallery.tpl}
