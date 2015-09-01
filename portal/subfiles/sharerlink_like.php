<?php

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', '../'); }

require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/database.class.php');

if (isset($_POST['sid']) && isset($_POST['fid'])) {

$dbcf = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$sharerID = $dbcf->prot(htmlspecialchars($_POST['sid']));
$likerID = $dbcf->prot(htmlspecialchars($_POST['fid']));
$dbcf->query("SELECT COUNT(*) FROM ip_shlikes WHERE sharer_id='$sharerID' AND user_id='$likerID'");
$checkValue = implode($dbcf->fetch_assoc());
$dbcf->close();

if ($checkValue == 1) {

$dbcfrem = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$dbcfrem->query("DELETE FROM ip_shlikes WHERE sharer_id='$sharerID' AND user_id='$likerID'");
$dbcfrem->close();

$dbch = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$dbch->query("SELECT COUNT(*) FROM ip_shlikes WHERE sharer_id='$sharerID'");
$total_like = implode($dbch->fetch_assoc());
$dbch->close();

if ($total_like == 0) { echo '0'; }
else { echo $total_like; }

} else {

$dbil = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$dbil->query("INSERT INTO ip_shlikes (sharer_id, user_id) VALUES ('$sharerID', '$likerID')");
$dbil->close();

$dbch = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$dbch->query("SELECT COUNT(*) FROM ip_shlikes WHERE sharer_id='$sharerID'");
$total_like = implode($dbch->fetch_assoc());
echo $total_like;
$dbch->close();

}

}

?>