<?php
/**
 * Related Topics
 *
 * @package RelatedTopics
 * @version 1.4
 */

function template_main()
{
	global $context, $modSettings, $txt, $related_version;

	echo '
	<div class="tborder floatleft" style="width: 69%;">
		<div class="cat_bar">
			<h3 class="catbg">', $txt['related_latest_news'], '</h3>
		</div>
		<div class="windowbg2 smallpadding">
			<span class="topslice"><span></span></span>
			<div id="related_news" style="overflow: auto; height: 18ex;" class="windowbg2 smallpadding">
				', $txt['related_news_unable_to_connect'], '
			</div>
		</div>
	</div>
	<div class="tborder floatright" style="width: 30%;">
		<div class="cat_bar">
			<h3 class="catbg headerpadding">', $txt['related_version_info'], '</h3>
		</div>
		<div class="windowbg2 smallpadding">
			<span class="topslice"><span></span></span>
			<div style="overflow: auto; height: 18ex;" class="windowbg2 smallpadding">
				', $txt['related_installed_version'], ': <span id="related_installed_version">', $related_version, '</span><br />
				', $txt['related_latest_version'], ': <span id="related_latest_version">???</span>
			</div>
			<span class="botslice"><span></span></span>
		</div>
	</div>
	<div style="clear: both"></div>
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		function setRelatedNews()
		{
			if (typeof(window.relatedNews) == "undefined" || typeof(window.relatedNews.length) == "undefined")
					return;

				var str = "<div style=\"margin: 4px; font-size: 0.85em;\">";

				for (var i = 0; i < window.relatedNews.length; i++)
				{
					str += "\n	<div style=\"padding-bottom: 2px;\"><a href=\"" + window.relatedNews[i].url + "\">" + window.relatedNews[i].subject + "</a> on " + window.relatedNews[i].time + "</div>";
					str += "\n	<div style=\"padding-left: 2ex; margin-bottom: 1.5ex; border-top: 1px dashed;\">"
					str += "\n		" + window.relatedNews[i].message;
					str += "\n	</div>";
				}

				setInnerHTML(document.getElementById("related_news"), str + "</div>");
		}

		function setRelatedVersion()
		{
			if (typeof(window.relatedCurrentVersion) == "undefined")
				return;

			setInnerHTML(document.getElementById("related_latest_version"), window.relatedCurrentVersion);
		}
	// ]]></script>
	<script language="JavaScript" type="text/javascript" src="http://service.smfarcade.info/related/news.js?v=', urlencode($related_version), '" defer="defer"></script>';

	template_show_settings();

	echo '
	<div class="cat_bar">
		<h3 class="catbg">', $txt['related_topics_index'], '</h3>
	</div>
	<div class="windowbg2">
		<span class="topslice"><span></span></span>
		
		<a href="', $scripturl, '?action=admin;area=relatedtopics;sa=buildIndex">', $txt['related_topics_rebuild'], '</a><br />
		<span class="smalltext">', $txt['related_topics_rebuild_desc'], '</span>
		
		<span class="botslice"><span></span></span>
	</div><br />';
}

function template_callback_related_methods()
{
	global $context, $modSettings, $txt, $related_version;
	
	echo '
	<dt>', $txt['related_topics_methods'], '</dt>
	<dd>';

	foreach ($context['related_methods'] as $id => $method)
		echo '
		<input type="checkbox" id="method_', $id, '" name="related_methods[]" value="', $id, '"', !$method['supported'] ? ' disabled="disabled"' : '', $method['selected'] ? ' checked="checked"' : '', '/> <label for="method_', $id, '">', $method['name'], '</label><br />';

	echo '
	</dd>';
}

function template_callback_ignored_boards()
{
	global $context, $modSettings, $txt, $related_version;
	
	echo '
	<dt>', $txt['related_topics_ignored_boards'], '</dt>
	<dd>';
	
	foreach ($context['categories'] as $cat)
	{
		echo '
		<strong>', $cat['name'], '</strong><br />';

		foreach ($cat['boards'] as $id => $board)
			echo '
		<input type="checkbox" id="ignored_boards_', $id, '" name="ignored_boards[]" value="', $id, '"', $board['selected'] ? ' checked="checked"' : '', '/> <label for="ignored_boards_', $id, '">', $board['name'], '</label><br />';
	}
	
	echo '
	</dd>';
}

?>