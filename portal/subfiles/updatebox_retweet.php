<?php

if (isset($_GET['retweet']) && !empty($_GET['retweet'])) {

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', '../'); }

require_once(SITE_ROOT.'portal_config.php');    
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');

$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$retweetID = $db->prot(htmlspecialchars($_GET['retweet']));
$db->query("SELECT shout_msg FROM ip_updates WHERE id='$retweetID'");
  
if ($row=$db->fetch_array()) {
  $shoutMsg = stripslashes(rtrim(htmlspecialchars_decode($row['shout_msg'])));
  $shoutMsg = str_ireplace("[rt]", "", $shoutMsg);
  $shoutMsg = str_ireplace("[/rt]", "", $shoutMsg);
  
  echo '[rt]'.$shoutMsg.'[/rt]';
} else {
  echo 'KO';
}

$db->close();

}

?>