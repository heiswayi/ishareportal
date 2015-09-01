<?php

if (isset($_GET['retweet'])) {

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', '../'); }

require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');

$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$retweetID = $db->prot(htmlspecialchars($_GET['retweet']));
$db->query("SELECT sharername,sharerurl FROM ip_sharerlinks WHERE id='$retweetID'");
  
if ($row=$db->fetch_array()) {
  $sharername = $row['sharername'];
  $sharerlink = $row['sharerurl'];
  echo '[ '.$sharername.' '.$sharerlink.' ]';
} else {
  echo 'KO';
}

$db->close();

}

?>