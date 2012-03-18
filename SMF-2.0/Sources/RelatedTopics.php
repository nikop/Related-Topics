<?php
/**
 * Related Topics
 *
 * @package RelatedTopics
 * @author Niko Pahajoki http://madjoki.com/
 * @version 1.5
 * @license http://madjoki.com/smf-mods/license/ New-BSD
 */

function RelatedTopicsAdminBuildIndex()
{
	global $smcFunc, $scripturl, $modSettings, $context, $txt;

	loadTemplate('Admin');
	loadLanguage('Admin');

	if (!isset($context['relatedClass']) && !initRelated())
		fatal_lang_error('no_methods_selected');
		
	$context['step'] = empty($_REQUEST['step']) ? 0 : (int) $_REQUEST['step'];

	if ($context['step'] == 0)
	{
		// Clear caches
		foreach ($context['relatedClass'] as $class)
			$class->recreateIndexTables();

		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}related_topics'
		);
	}

	$request = $smcFunc['db_query']('', '
		SELECT MAX(id_topic)
		FROM {db_prefix}topics');
	list ($max_topics) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	// How many topics to do per page load?
	$perStep = 150;
	$last = $context['step'] + $perStep;

	// Search for topic ids between first and last which are not in ignored boards
	$request = $smcFunc['db_query']('', '
		SELECT t.id_topic
		FROM {db_prefix}topics AS t
		WHERE t.id_topic > {int:start}
			AND t.id_topic <= {int:last}' . (!empty($context['rt_ignore']) ? '
			AND t.id_board NOT IN({array_int:ignored})' : ''),
		array(
			'start' => $context['step'],
			'last' => $last,
			'ignored' => $context['rt_ignore'],
		)
	);

	$topics = array();

	while ($row =  $smcFunc['db_fetch_assoc']($request))
		$topics[] = $row['id_topic'];
	$smcFunc['db_free_result']($request);

	// Update topics
	relatedUpdateTopics($topics, true);

	if ($last >= $max_topics)
		redirectexit('action=admin;area=relatedtopics;sa=methods');

	$context['sub_template'] = 'not_done';
	$context['continue_get_data'] = '?action=admin;area=relatedtopics;sa=buildIndex;step=' . $last;

	$context['continue_percent'] = round(100 * ($last / $max_topics));
	$context['continue_post_data'] = '';
	$context['continue_countdown'] = '2';
	
	obExit();
}

?>