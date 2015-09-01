<?php

if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }

require_once(FORUM_ROOT.'include/common.php'); // (required) // session_start()
require_once(SITE_ROOT.'include/html.php'); // HTML structures
require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/database.class.php');

function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

// Check current user session ID
if ($forum_user['is_guest']) { header('Location: login.php'); }

if (!isset($_SESSION['current_userID'])) { $_SESSION['current_userID'] = $forum_user['id']; }
else { $_SESSION['current_userID'] = $forum_user['id']; }

$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$uid = $forum_user['id'];
$uip = get_client_ip();
$db->query("UPDATE forum_users SET registration_ip='$uip' WHERE id='$uid'");
$db->close();

// Load top level HTML structures
html('start');
html_meta();
html_css();
html_favicon();
html_jquery();
html('body');
html_header();

?>

<div class="container">
    
<div class="row-fluid">
    
<?php include(SITE_ROOT.'side_left.php'); ?>
    
<div class="span6">

<?php
if ($forum_config['o_announcement'] == 1) {
echo '<div class="alert alert-block"><h4>'.$forum_config['o_announcement_heading'].'</h4>'.$forum_config['o_announcement_message'].'</div>';
}
?>
    
<div id="console" class="alert alert-error" style="display:none"></div>

<form id="shoutForm" class="form-horizontal">

<div class="bbcode-toolbar">
<div class="btn-group" style="margin:0"><span class="btn btn-small" onmousedown="bbCodeWrap('quote'); return false;"><i class="icon-quote-left"></i> Quote</span></div>
<div class="btn-group" style="margin:0">
<span class="btn btn-small dropdown-toggle" data-toggle="dropdown"><i class="icon-th-large"></i> Text Colors <span class="caret"></span></span>
  <ul class="dropdown-menu">
    <li><span class="color color-purple" onmousedown="bbCodeWrap('purple'); return false;"><i class="icon-sign-blank"></i> Purple</span></li>
    <li><span class="color color-blue" onmousedown="bbCodeWrap('blue'); return false;"><i class="icon-sign-blank"></i> Blue</span></li>
    <li><span class="color color-teal" onmousedown="bbCodeWrap('teal'); return false;"><i class="icon-sign-blank"></i> Teal</span></li>
    <li><span class="color color-green" onmousedown="bbCodeWrap('green'); return false;"><i class="icon-sign-blank"></i> Green</span></li>
    <li><span class="color color-orange" onmousedown="bbCodeWrap('orange'); return false;"><i class="icon-sign-blank"></i> Orange</span></li>
    <li><span class="color color-pink" onmousedown="bbCodeWrap('pink'); return false;"><i class="icon-sign-blank"></i> Pink</span></li>
    <li><span class="color color-red" onmousedown="bbCodeWrap('red'); return false;"><i class="icon-sign-blank"></i> Red</span></li>
  </ul>
</div>
<span class="bbcode-label pull-left">bbCode <i class="icon-arrow-right"></i></span>
</div>
<div id="textareaBase">
<textarea id="shoutTextarea" class="input-block-level msg-box" placeholder="Type your message here" rows="3" maxlength="1000"></textarea>
</div>
<input type="hidden" id="shoutUserId" value="<?php echo $forum_user['id']; ?>">
      
<div class="post-control">
<a href="#ipStats" data-toggle="modal" class="btn btn-info tip-top" title="IsharePortal Statistics"><i class="icon-bar-chart"></i></a>
<span id="charCount" class="charCount tip-top" style="background:#ccc;font-weight:bold" title="Characters Limit"></span><span id="onlineCount" class="charCount tip-top" style="background:#e3e3e3;color:#555" title="Logged-in Users">Loading..</span>

<div class="btn-group pull-right">
  <button class="btn btn-emoticon" title="Tuzki Club" type="button" id="showTuzki"><i class="launcher tuzki-club"></i></button>
  <button class="btn btn-emoticon" title="Onion Club" type="button" id="showOnion"><i class="launcher onion-club"></i></button>
  <button class="btn btn-emoticon" title="Sweet Smileys" type="button" id="showSmileys"><i class="launcher default-smileys"></i></button>
  <button id="shoutButton" class="btn btn-success"><i class="icon-pencil"></i> SHOUT</button>
  <button id="clearButton" class="btn btn-warning" title="Clear"><i class="icon-trash"></i></button>
</div>
</div><!-- /.post-control -->
     
<div class="emoticons-space" style="display:none;"></div>

</form>

<div class="combo-section">
<ul class="nav nav-tabs combo-nav" id="threebox">
  <li class="active"><a href="#shoutbox"><i class="icon-comments-alt"></i> Shoutbox <button type="button" onClick="loadShoutbox()" class="btn btn-mini tip-top" title="Due to the limitation of connections per IP set by PPKT for browsing IsharePortal, sometimes the shout messages posted by others do not display automatically on top of the shoutbox. So, you need to click this button to refresh the shoutbox. No need browser refresh button!"><i class="icon-refresh"></i></button></a></li>
  <li><a href="#updatebox"><i class="icon-list-alt"></i> Latest Updates</a></li>
  <li><a href="#requestbox"><i class="icon-list-alt"></i> Latest Requests</a></li>
</ul>
      
<div class="tab-content combo-content">

<!-- (displayNewShout)
<div class="tab-pane active" id="shoutboxTab">
<div id="displayNewShoutBtn"></div>
<div id="shoutbox"></div>
</div><!-- /#shoutboxTab
-->

<div class="tab-pane active" id="shoutbox"></div><!-- /#shoutbox -->
        
<div class="tab-pane" id="updatebox"></div><!-- /#updatebox -->
        
<div class="tab-pane" id="requestbox"></div><!-- /#requestbox -->
        
</div><!-- /.combo-content -->     
</div><!-- /.combo-section -->
      
</div>    

<?php include(SITE_ROOT.'side_right.php'); ?>
    
</div><!-- /.row-fluid -->

</div><!-- /.container -->
    
<?php html_footer(); ?>

<div id="ipStats" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="myModalLabel">IsharePortal Statistics</h3>
  </div>
  <div class="modal-body">
<div class="stat-data">
<?php
require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/database.class.php');
$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$t_users = $db->num($db->query("SELECT id FROM forum_users")) - 1;
$t_shouts = $db->num($db->query("SELECT id FROM ip_shouts"));
$t_updates = $db->num($db->query("SELECT id FROM ip_updates"));
$t_requests = $db->num($db->query("SELECT id FROM ip_requests"));
$t_reply = $db->num($db->query("SELECT id FROM ip_reply"));
$t_sharerlinks = $db->num($db->query("SELECT id FROM ip_sharerlinks"));
echo 'Total of registered users: <strong>'.$t_users.' usernames</strong><br/>';
echo 'Total of current shouts: <strong>'.$t_shouts.' messages</strong><br/>';
echo 'Total of <code>!update</code> shouts: <strong>'.$t_updates.' messages</strong><br/>';
echo 'Total of <code>!request</code> shouts: <strong>'.$t_requests.' messages</strong><br/>';
echo 'Total of replies: <strong>'.$t_reply.' messages</strong><br/>';
echo 'Total of sharerlinks: <strong>'.$t_sharerlinks.' links</strong><br/>';
$top5 = $db->query("SELECT user_id, COUNT(user_id) AS top5 FROM ip_shouts GROUP BY user_id ORDER BY top5 DESC LIMIT 5");
$rank = 1;
echo 'Top 5 Shouters:<br/>';
while ($row=$db->fetch_assoc($top5)) {
  $userID = $row['user_id'];
  $username = implode($db->fetch_assoc($db->query("SELECT username FROM forum_users WHERE id='$userID'")));
  $shoutno = $db->num($db->query("SELECT shout_msg FROM ip_shouts WHERE user_id='$userID'"));
  echo '<span class="label label-inverse tip-top" title="'.$shoutno.' shouts"><span class="icon-red">'.$rank.'</span> '.$username.'</span> ';
  $rank++;
}
echo '<br/>';
$top3 = $db->query("SELECT sharer_id, COUNT(sharer_id) AS top3 FROM ip_shlikes GROUP BY sharer_id ORDER BY top3 DESC LIMIT 3");
$srank = 1;
echo 'Top 3 Most-liked Sharerlinks:<br/>';
while ($row=$db->fetch_assoc($top3)) {
  $sharerID = $row['sharer_id'];
  $sharername = implode($db->fetch_assoc($db->query("SELECT sharername FROM ip_sharerlinks WHERE id='$sharerID'")));
  $shlikes = $db->num($db->query("SELECT id FROM ip_shlikes WHERE sharer_id='$sharerID'"));
  echo '<span class="label label-inverse tip-top" title="'.$shlikes.' likes"><span class="icon-red">'.$srank.'</span> '.$sharername.'</span> ';
  $srank++;
}
$db->close();
?>
</div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

<div id="idleScreen" class="idle-fullscreen"></div>
<div id="idleText" class="idle-hi">
<img src="assets/img/logo.png">
<p><i class="icon-time icon-white semi-transparent"></i> You have been <strong>idle</strong> for more than 15 minutes.</p>
</div>
<div id="idleTextAway" class="idle-hi-away">
<img src="assets/img/logo.png">
<p><i class="icon-time icon-white semi-transparent"></i> You have been <strong>away</strong> for more than 60 minutes.</p>
<button id="siteReload" class="btn btn-danger"><i class="icon-refresh"></i> Reload</button>
</div>

<?php html_script(); ?>
<script>
var startTimer;
var checkUserSession = setInterval(checkSession, 5000);
var checkUserOnline = setInterval(checkOnline, 6000);
var initFirstCheck = true;
var shoutCount = 0;
var newShout = 0;
var thisIsMe = false;
var avoidFlood = false;
var displayNewShout = 0;

initAutoGrow();
function initAutoGrow(){$('#shoutTextarea').autogrow();}

// SHOUT button processing
$('#shoutButton').click(function (e) {
  e.preventDefault();
  if (avoidFlood == false) {
  avoidFlood = true;
  clearTimeout(startTimer);
  thisIsMe = true;
  var shoutMsg = $('#shoutTextarea').val();
  var userID = $('#shoutUserId').val();
  var dataString = 'shoutMsg=' + urlencode(shoutMsg) + '&userID=' + urlencode(userID);
  $.ajax({
    type: 'POST',
    url: 'shoutbox_display.php',
    data: dataString,
    success: function (html) {
      if (html == 'OK') {
        $("#shoutTextarea").val("");
        $("#charCount").text("500");
        $('#textareaBase').html('<textarea id="shoutTextarea" class="input-block-level msg-box" placeholder="Type your message here" rows="3" maxlength="1000"></textarea>');
        initAutoGrow();
        refreshShoutbox();
      } else {
        $("#console").html(html).fadeIn().delay(3000).fadeOut();
      }
    }
  });
  } else {
    $("#console").html('Flood Protection: Your posted shout does not appear yet, please wait!').fadeIn().delay(3000).fadeOut();
  }
});

var shoutboxTab = false;
var updateTab = false;
var requestTab = false;
// Shoutbox tab, Updates tab & Requests tab
$('#threebox a[href="#shoutbox"]').click(function (e) {
  e.preventDefault();
  if (shoutboxTab == false) {
  $('#threebox a[href="#shoutbox"]').tab('show');
  $("#updatebox").html('');
  $("#requestbox").html('');
  loadShoutbox();
  shoutboxTab = true;
  updateTab = false;
  requestTab = false;
  }  
});
$('#threebox a[href="#updatebox"]').click(function (e) {
  e.preventDefault();
  if (updateTab == false) {
  $('#threebox a[href="#updatebox"]').tab('show');
  $("#shoutbox").html('');
  $("#requestbox").html('');
  $("#updatebox").html('<div class="loader" style="margin-top:100px"></div>');
  $.ajax({
    type: 'GET',
    url: 'updatebox_display.php?display=1&no_cache='+Math.random(),
    success: function (data) {
      $("#updatebox").html(data).fadeIn();
    }
  });
  shoutboxTab = false;
  updateTab = true;
  requestTab = false;
  }
});
$('#threebox a[href="#requestbox"]').click(function (e) {
  e.preventDefault();
  if (requestTab == false) {
  $('#threebox a[href="#requestbox"]').tab('show');
  $("#updatebox").html('');
  $("#shoutbox").html('');
  $("#requestbox").html('<div class="loader" style="margin-top:100px"></div>');
  $.ajax({
    type: 'GET',
    url: 'requestbox_display.php?display=1&no_cache='+Math.random(),
    success: function (data) {
      $("#requestbox").html(data).fadeIn();
    }
  });
  shoutboxTab = false;
  updateTab = false;
  requestTab = true;
  }
});

initShoutbox();
checkOnline();

function initShoutbox() {
  $("#shoutbox").html('<div class="loader" style="margin-top:100px"></div>');
  $.ajax({
    type: 'GET', url: 'shoutbox_display.php?display=1&no_cache='+Math.random(),
    success: function(data){
      $('#threebox a[href="#shoutbox"]').tab('show');
      shoutboxTab = true;
      $("#shoutbox").html(data).fadeIn();
      startTimer = setTimeout(checkNewShout, 1000);
    }
  });
}
function loadShoutbox() {
  $.ajax({
    type: 'GET', url: 'shoutbox_display.php?display=1&no_cache='+Math.random(),
    success: function(data){
      $("#shoutbox").html(data).fadeIn();
    }
  });
}
function refreshShoutbox() {
  $.ajax({
    type: 'GET', url: 'shoutbox_display.php?display=1&no_cache='+Math.random(),
    success: function(data){
      $('#threebox a[href="#shoutbox"]').tab('show');
      $("#shoutbox").html(data).fadeIn();
      shoutCount = shoutCount + 1;
      avoidFlood = false;
      startTimer = setTimeout(checkNewShout, 1000);
    }
  });
}
function checkNewShout() {
  clearTimeout(startTimer);
  $.ajax({
    type: 'GET', url: 'subfiles/shoutbox_count.php?i=' + Math.random(),
    success: function(data){
      if (initFirstCheck == true) {
        shoutCount = parseInt(data);
        initFirstCheck = false;
        checkFocus();
        startTimer = setTimeout(checkNewShout, 1000);
      } else {
        if (parseInt(data) > shoutCount) {
          shoutCount = parseInt(data);
          newShout = newShout + 1;
          loadShoutbox();
          checkFocus();
          startTimer = setTimeout(checkNewShout, 1000);
        } else {
          checkFocus();
          startTimer = setTimeout(checkNewShout, 1000);
        }
      }
    }
  });
}
function checkSession(){
  $.ajax({
    type: 'GET', url: 'session.php?check=1&i=' + Math.random(),
    success: function(data){
      if (data == 'NotLoggedIn') { window.location.replace('login.php'); }
    }
  });
}
function checkFocus() {
  if (document.hasFocus()) {
    if (newShout > 0) { newShout = 0; document.title = "IsharePortal"; }
  } else {
    if (newShout > 0) { document.title = "("+newShout+") IsharePortal"; }
  }
}
function checkOnline(){
  $.ajax({
    type: 'GET', url: 'subfiles/online_user_count.php?i=' + Math.random(),
    success: function(data){
      $("#onlineCount").html(data+' online');
    }
  });
}
</script>

<?php echo html('end'); ?>
