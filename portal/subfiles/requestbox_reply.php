<?php

if (session_id() == '') { session_start(); }

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', '../'); }

require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');

$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['requestMsgID']) && isset($_POST['replyMsg']) && isset($_POST['userID']) && isset($_POST['cts'])) {

  $replyTime = time();
  $requestMsgID = $db->prot(htmlspecialchars($_POST['requestMsgID']));
  $replyMsg = $db->prot(htmlspecialchars($_POST['replyMsg']));
  $replierID = $db->prot(htmlspecialchars($_POST['userID']));
  
  $db2 = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $db2->query("SELECT user_id FROM ip_requests WHERE id='$requestMsgID'");
  if ($row2=$db2->fetch_array()) {
    $requestUserID = $row2['user_id'];
    $dbf = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $dbf->query("SELECT username FROM forum_users WHERE id='$requestUserID'");
    if ($rowf=$dbf->fetch_array()) {
      $reqUsername = $rowf['username'];
    }
    $dbf->close();
  }
  $db2->close();
  
  if ($replyMsg == '') {
    echo 'ERROR! Nothing to reply? Keep that confuse away.';
  } else {
    if ($_POST['cts'] == 1) {
      $tempMsg = '<code>Replied to @'.$reqUsername.'</code> '.$replyMsg;
      $shoutTime = time();
      $db->query("INSERT INTO ip_shouts (shout_time, shout_msg, user_id) VALUES ('$shoutTime', '$tempMsg', '$replierID')");
      $db->query("INSERT INTO ip_reply (request_id, reply_time, reply_msg, replier_id) VALUES ('$requestMsgID', '$replyTime', '$replyMsg', '$replierID')");
      echo 'OK';
    } else {
      $db->query("INSERT INTO ip_reply (request_id, reply_time, reply_msg, replier_id) VALUES ('$requestMsgID', '$replyTime', '$replyMsg', '$replierID')");
      echo 'OK';
    }
  }
  
} else { header('Location: '.SITE_ROOT.'404.php'); }

$db->close();

?>