<?php
 
header('Content-type: text/html; charset=utf-8');
 
// Get all topics
$query = array(
	'SELECT'	=> 't.id, subject, t.last_post, forum_id',
	'FROM'		=> 'topics AS t',
	'JOINS'		=>	array(
		array(
			'INNER JOIN'	=>	'forums AS f',
			'ON'		=>	'f.id = t.forum_id'
		)
	),
	'ORDER BY'	=> 'last_post DESC',
	'LIMIT' => '0,10',
);
 
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
 
if (!$forum_user['is_guest'])
	$tracked_topics = get_tracked_topics();
 
?>
<ul>
<?php
 
while ($cur_topic = $forum_db->fetch_assoc($result))
{
	echo '<li>';
 
	if (!$forum_user['is_guest'] && $cur_topic['last_post'] > $forum_user['last_visit']
		&& (!isset($tracked_topics['topics'][$cur_topic['id']]) || $tracked_topics['topics'][$cur_topic['id']] < $cur_topic['last_post'])
		&& (!isset($tracked_topics['forums'][ $cur_topic['forum_id'] ]) || $tracked_topics['forums'][ $cur_topic['forum_id'] ] < $cur_topic['last_post']))
		echo '[unread]&nbsp;';
	else
		echo '[read]&nbsp;';
	echo '<a href="', forum_link($forum_url['topic'], $cur_topic['id']), '">', $cur_topic['subject'], '</a>';
 
	echo '</li>';
}
 
?>
</ul>