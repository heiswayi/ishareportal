<?php

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', '../'); }
    
require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/database.class.php');
require_once(SITE_ROOT.'include/functions.php');

if (isset($_GET['slid'])) {
   
$dbsls = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$sharerLinkID = $dbsls->prot(htmlspecialchars($_GET['slid']));
$dbsls->query("SELECT * FROM ip_sharerlinks WHERE id='$sharerLinkID'");

if ($sls=$dbsls->fetch_array()) {
  $url = $sls['sharerurl'];
  $removehttp = str_replace('http://', '', $url);
  $removeslash = rtrim($removehttp, '/');
  if (strpos($removeslash, ':') !== false) {
    list($ip, $port) = explode(":", $removeslash);
  } else {
    $ip = $removeslash;
    $port = 80;
  }
}

if (fsockopen($ip, $port, $errno, $errstr, 5) !== false) {
  echo '1';
  $dbsls->query("UPDATE ip_sharerlinks SET status='1' WHERE id='$sharerLinkID'");
} else {
  echo '0';
  $dbsls->query("UPDATE ip_sharerlinks SET status='0' WHERE id='$sharerLinkID'");
}

$dbsls->close();

} else if (isset($_GET['state'])) {

$dbcs = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$dbcs->query("SELECT * FROM ip_sharerlinks ORDER BY status");

while($getr=$dbcs->fetch_assoc()) {
  $state = $getr['status'];
  echo $state;
}

$dbcs->close();

} else { echo '404'; }

?>