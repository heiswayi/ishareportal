<?php

if (session_id() == '') { session_start(); }

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }

require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');

if (isset($_POST['init']) && isset($_POST['userID'])) {
   
  $dbs = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $userID = $dbs->prot(htmlspecialchars($_POST['userID']));
  $dbs->query("SELECT COUNT(*) FROM ip_sharerlinks WHERE user_id='$userID'");
  $checkSharer = implode($dbs->fetch_assoc());
  $dbs->close();
  
  $db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $db->query("SELECT * FROM ip_sharerlinks WHERE user_id='$userID'");
    
  if ($checkSharer == 0) {
    echo '0';
  } else {
    if ($row=$db->fetch_array()) {
      $sharername = $row['sharername'];
      $sharerlink = $row['sharerurl'];
      $sharerdesc = htmlspecialchars_decode($row['sharerdesc']);
      echo $sharername.'[]'.$sharerlink.'[]'.$sharerdesc;
    }
  }
  
  $db->close();
  
} else if (isset($_POST['update']) && isset($_POST['userID']) && isset($_POST['sharername']) && isset($_POST['sharerlink']) && isset($_POST['sharerdesc'])) {

  $db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  
  $sharerdate = time();
  $userID = $db->prot(htmlspecialchars($_POST['userID']));
  $sharername = $db->prot(htmlspecialchars($_POST['sharername']));
  $sharerlink = $db->prot(htmlspecialchars($_POST['sharerlink']));
  $sharerdesc = $db->prot(htmlspecialchars($_POST['sharerdesc']));
  
  if (strpos($sharerlink, "http://") === 0) { $slink = $sharerlink; }
  else { $slink = 'http://'.$sharerlink; }
    
  if ($sharername == '' || $sharerlink == '' || $sharerdesc == '') {
    echo 'ERROR! All fields are required.';
  } else if (!validateSharername($sharername)) {
    echo 'ERROR! Only alphanumerics, underscore, dot and space are allowed.';
  } else {
    $db->query("UPDATE ip_sharerlinks SET sharername='$sharername', sharerurl='$slink', sharerdesc='$sharerdesc' WHERE user_id='$userID'");
    echo 'OK';
  }
  
  $db->close();

} else if (isset($_POST['delete']) && isset($_POST['userID'])) {
  
  $db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $userID = $db->prot(htmlspecialchars($_POST['userID']));
  $db->query("SELECT id FROM ip_sharerlinks WHERE user_id='$userID'");
  if ($row=$db->fetch_array()) { $sharerlinkID = $row['id']; }
  $db->query("DELETE FROM ip_sharerlinks WHERE user_id='$userID'");
  $db->query("DELETE FROM ip_shlikes WHERE sharer_id='$sharerlinkID'");
  echo 'DEL';
  $db->close();
  
} else { header('Location: '.SITE_ROOT.'404.php'); }

?>