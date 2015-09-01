<?php

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }

require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');

$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['action']) && isset($_POST['uid'])) {
  if ($_POST['action'] == 'eliminate') {
    $uid = $_POST['uid'];
    $db->query("DELETE FROM forum_users WHERE id='$uid'");
    echo 'OK';
  }
  if ($_POST['action'] == 'ban') {
    $uid = $_POST['uid'];
    $uip = $_POST['uip'];
    $db->query("INSERT INTO ip_bans (user_id, user_ip) VALUES ('$uid', '$uip')");
    echo 'OK';
  }
  if ($_POST['action'] == 'unban') {
    $uid = $_POST['uid'];
    $db->query("DELETE FROM ip_bans WHERE user_id='$uid'");
    echo 'OK';
  }
  if ($_POST['action'] == 'remove') {
    $uid = $_POST['uid'];
    $db->query("DELETE FROM ip_sharerlinks WHERE user_id='$uid'");
    echo 'OK';
  }
}

$db->close();

?>