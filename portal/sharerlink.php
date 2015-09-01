<?php

if (session_id() == '') { session_start(); }
$currentUserId = $_SESSION['current_userID'];
    
if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }
    
require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');

if (isset($_GET['display']) && $_GET['display'] == 1) {
    
$dbsl = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$dbsl->query("SELECT * FROM ip_sharerlinks ORDER BY status DESC, sharername ASC");

$dbsc = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$dbsc->query("SELECT COUNT(*) FROM ip_sharerlinks");
$total_sharer = implode($dbsc->fetch_assoc());
$dbsc->close();

if ($total_sharer > 0) {

echo '<ul id="sharerlink" class="dashboard-list sharerlink">';

while($rowsl=$dbsl->fetch_assoc()) {
  $sharerId = $rowsl['id'];
  $sharerName = $rowsl['sharername'];
  $sharerLink = $rowsl['sharerurl'];
  $sharerDesc = $rowsl['sharerdesc'];
  $sharerOwner = $rowsl['user_id'];
  $sharerStatus = $rowsl['status'];
  
  echo '<li id="sharerlink-'.$sharerId.'">';
  
  echo '<span id="indicator-'.$sharerId.'"></span>';
  
  echo '<div class="sharerlink-data">';
  
  echo '<strong><a href="'.$sharerLink.'" id="sharerURL" title="'.$sharerLink.'" target="_blank">'.$sharerName.'</a></strong><br>';
  
  echo '<span class="sharerlink-desc">'.stripslashes(rtrim($sharerDesc)).'</span>';
  
  // Like button
  echo '<div id="favBtn" style="padding-top:10px;">';
  $dbfav = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $dbfav->query("SELECT COUNT(*) FROM ip_shlikes WHERE sharer_id='$sharerId'");
  $total_fav = implode($dbfav->fetch_assoc());
  $dbfav->close();
  if ($total_fav > 0) {
    echo '<button id="btnLike favThisID-'.$sharerId.'" class="btn btn-mini btn-danger tip-top" title="People love this" onClick="favThis(\''.$sharerId.'\',\''.$currentUserId.'\')"><i class="icon-heart icon-white"></i> <span id="shfavTotal-'.$sharerId.'">'.$total_fav.'</span></button>';
  } else {
    echo '<button id="btnLike favThisID-'.$sharerId.'" class="btn btn-mini btn-danger tip-top" title="People love this" onClick="favThis(\''.$sharerId.'\',\''.$currentUserId.'\')"><i class="icon-heart icon-white"></i> <span id="shfavTotal-'.$sharerId.'">0</span></button>';
  }
  echo ' <button class="btn btn-mini tip-top" title="Reshout this sharerlink" onClick="rtsh(\''.$sharerId.'\');"><i class="icon-share"></i> RT</button>';
  echo '</div>';
  // End of Like button
  
  echo '</div></li>';
  
  echo '
  <script>
  $(document).ready(function () { // START DOCUMENT.READY

  checkStatus'.$sharerId.'('.$sharerId.');
  setInterval(function(){checkStatus'.$sharerId.'('.$sharerId.');}, 240000);
  
  }); // END DCOUMENT.READY

  function checkStatus'.$sharerId.'(sharerID) {
    $("#indicator-'.$sharerId.'").html("<img class=\"indicator\" src=\"assets/img/loader.gif\" title=\"Checking...\">");
    $.ajax({
      type: "GET", url: "subfiles/sharerlink_check.php?slid=" + sharerID + "&i=" + Math.random(),
      success: function(data){
        if (data == 1) {
          $("#indicator-'.$sharerId.'").html("<img class=\"indicator\" alt=\"ONLINE\" src=\"assets/img/online.gif\" title=\"ONLINE\">");
        } else {
          $("#indicator-'.$sharerId.'").html("<img class=\"indicator\" alt=\"OFFLINE\" src=\"assets/img/offline.gif\" title=\"OFFLINE\">");
        }
      }
    });
  }
  </script>
  ';
  
}

echo '</ul>';

echo '
<script>
$(document).ready(function () { // START DOCUMENT.READY

$("#sharerURL, .tip-top").tooltip();

}); // END DCOUMENT.READY

function urlencode(a) {
  a = (a + "").toString();
  return encodeURIComponent(a).replace(/!/g, "%21").replace(/\'/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\*/g, "%2A").replace(/%20/g, "+")
}
function favThis(sharerID,curUserID){
  var dataString = "sid=" + urlencode(sharerID) + "&fid=" + urlencode(curUserID);
  $.ajax({
    type: "POST", url: "subfiles/sharerlink_like.php", data: dataString,
    success: function(html){ $("#shfavTotal-" + sharerID).html(html); }
  });
}
function rtsh(sharerid){      
  $.ajax({
    type: "GET", url: "subfiles/sharerlink_retweet.php?retweet=" + urlencode(sharerid),
    success: function(html){
      if (html !== "KO") { $("#shoutTextarea").val("RT: " + html); }
    }
  });
}
</script>
';

$dbsl->close();

} else {
  echo '<div class="alert alert-info"><h4>Oops!</h4>No sharerlink added yet. To add a sharerlink, simply click on plus (+) sign button above and proceed with the form.</div>';
}

}

?>