<?php

if (session_id() == '') { session_start(); }

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }

require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');

$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['add']) && isset($_POST['userID']) && isset($_POST['sharername']) && isset($_POST['sharerlink']) && isset($_POST['sharerdesc'])) {
  
  $dbs = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  
  $userID = $dbs->prot(htmlspecialchars($_POST['userID']));
  $sharerdate = time();
  $sharername = $dbs->prot(htmlspecialchars($_POST['sharername']));
  $sharerlink = $dbs->prot(htmlspecialchars($_POST['sharerlink']));
  $sharerdesc = $dbs->prot(htmlspecialchars($_POST['sharerdesc']));
  
  if (strpos($sharerlink, "http://") === 0) { $slink = $sharerlink; }
  else { $slink = 'http://'.$sharerlink; }
  
  
  $dbs->query("SELECT COUNT(*) FROM ip_sharerlinks WHERE user_id='$userID'");
  $checkSharer = implode($dbs->fetch_assoc());
  $dbs->close();
    
  if ($checkSharer == 1) {
    echo 'ERROR! You already added a sharerlink. Only one sharerlink per single user.';
  } else if ($userID == '' || $sharername == '' || $sharerlink == '' || $sharerdesc == '') {
    echo 'ERROR! All fields are required.';
  } else if (!validateSharername($sharername)) {
    echo 'ERROR! Only alphanumerics, underscore, dot and space are allowed.';
  } else {
    $db->query("INSERT INTO ip_sharerlinks (user_id, sharername, sharerurl, sharerdesc, sharerdate) VALUES ('$userID', '$sharername', '$slink', '$sharerdesc', '$sharerdate')");
    echo 'OK';
  }
  
} else { header('Location: '.SITE_ROOT.'404.php'); }

$db->close();

?>