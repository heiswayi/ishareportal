<?php

if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }

require_once(FORUM_ROOT.'include/common.php'); // (required) // session_start()
require_once(SITE_ROOT.'include/html.php'); // HTML structures

// Check current user session ID
$currentUserID = $forum_user['id'];
if ($forum_user['is_guest']) { header('Location: login.php'); } // If not logged in, forward to login.php

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

<div class="well">

<div class="alert alert-info"><span class="label label-info">Disclaimer</span> Ishare will not be responsible for whatever you're sharing. Ishare simply provides a service to sharers to put their sharerlink so that it's easily found by the users. Any consequence will reflect to the respective owner.</div>

<div id="sleconsole" class="alert alert-error" style="display:none"></div>
<div id="sleconsole-success" class="alert alert-success" style="display:none"></div>

<form class="form-horizontal" id="editSharerlink">
<legend id="editTitle">Edit Sharerlink</legend>
  <div class="control-group">
    <label class="control-label" for="inputSharername">Name:</label>
    <div class="controls">
      <input type="text" id="inputSharername" class="sharername" placeholder="Title of sharerlink" value="" maxlength="25">
      <i class="icon-info-sign tip-top" id="sinfo" title="Only alphanumerics, underscore, dot and space are allowed"></i>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputSharerlink">Link:</label>
    <div class="controls">
      <input type="text" id="inputSharerlink" class="sharerlink" placeholder="http://" value="" maxlength="50">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputSharerdesc">Description:</label>
    <div class="controls">
      <textarea id="inputSharerdesc" class="sharerdesc" style="resize:none" rows="4" maxlength="140"></textarea>
    </div>
  </div>
  <input type="hidden" id="currentUser" value="<?php echo $currentUserID; ?>">
  <div class="form-actions">
    <button id="updateBtn" class="btn btn-primary">Update changes</button>
    <button id="deleteBtn" class="btn btn-danger">Delete</button>
    <button id="resetBtn" class="btn btn-inverse">Reset</button>
  </div>
</form>

</div>
      
</div>    

<?php include(SITE_ROOT.'side_right.php'); ?>
    
</div><!-- /.row-fluid -->

</div><!-- /.container -->
    
<?php html_footer(); ?>

<div id="idleScreen" class="idle-fullscreen"></div>
<div id="idleText" class="idle-hi">
<img src="assets/img/logo.png">
<p><i class="icon-time icon-white semi-transparent"></i> You have been <strong>idle</strong> for more than 15 minutes.</p>
</div>
<div id="idleTextAway" class="idle-hi-away">
<img src="assets/img/logo.png">
<p><i class="icon-time icon-white semi-transparent"></i> You have been <strong>away</strong> for more than 60 minutes.</p>
<button id="siteReload" class="btn btn-danger"><i class="icon-refresh icon-white"></i> Reload</button>
</div>

<?php html_script(); ?>

<script>
var initdata;
var addLink = false;

initSLData('<?php echo $currentUserID; ?>');
$("#updateBtn").click(function (e) {
  e.preventDefault();
  if (addLink == false) {
    var userID = $("#currentUser").val();
    var sharername = $("#inputSharername").val();
    var sharerlink = $("#inputSharerlink").val();
    var sharerdesc = $("#inputSharerdesc").val();
    var dataString = "update=1&userID=" + urlencode(userID) + "&sharername=" + urlencode(sharername) + "&sharerlink=" + urlencode(sharerlink) + "&sharerdesc=" + urlencode(sharerdesc);
    $.ajax({
      type: "POST",
      url: "sharerlink_edit.php",
      data: dataString,
      success: function (html) {
        if (html == "OK") {
          $("#sleconsole-success").html('<h4>Success!</h4>Your sharerlink has been updated.').fadeIn().delay(5000).fadeOut();
          refreshList();
        } else {
          $("#sleconsole").html(html).fadeIn().delay(5000).fadeOut();
        }
      }
    });
  } else {
    var userID = $("#currentUser").val();
    var sharername = $("#inputSharername").val();
    var sharerlink = $("#inputSharerlink").val();
    var sharerdesc = $("#inputSharerdesc").val();
    var dataString = "add=1&userID=" + urlencode(userID) + "&sharername=" + urlencode(sharername) + "&sharerlink=" + urlencode(sharerlink) + "&sharerdesc=" + urlencode(sharerdesc);
    $.ajax({
      type: "POST",
      url: "sharerlink_add.php",
      data: dataString,
      success: function (html) {
        if (html == "OK") {
          $("#sleconsole-success").html('<h4>Success!</h4>Your sharerlink has been added to our database.').fadeIn().delay(5000).fadeOut();
          $('#editTitle').html('Edit Sharerlink');
          $('#updateBtn').html('Update changes');
          addLink = false;
          refreshList();
        } else {
          $("#sleconsole").html(html).fadeIn().delay(5000).fadeOut();
        }
      }
    });
  }
});
$("#deleteBtn").click(function (e) {
  e.preventDefault();
  var userID = $("#currentUser").val();
  var dataString = "delete=1&userID=" + urlencode(userID);
  $.ajax({
    type: "POST",
    url: "sharerlink_edit.php",
    data: dataString,
    success: function (html) {
      if (html == "DEL") {
        $("#sleconsole-success").html('<h4>Success!</h4>Your sharerlink has been removed.').fadeIn().delay(5000).fadeOut();
        $("#inputSharername").val('');
        $("#inputSharerlink").val('');
        $("#inputSharerdesc").val('');
        $('#editTitle').html('Add a Sharerlink');
        $('#updateBtn').html('Submit');
        $('#deleteBtn').hide();
        addLink = true;
        refreshList();
      } else {
        $("#sleconsole").html(html).fadeIn().delay(5000).fadeOut();
      }
    }
  });
});
$("#resetBtn").click(function (e) {
  e.preventDefault();
  $("#inputSharername").val('');
  $("#inputSharerlink").val('');
  $("#inputSharerdesc").val('');
});
var checkUserSession = setInterval(checkSession, 5000);
function checkSession(){
  $.ajax({
    type: 'GET', url: 'session.php?check=1&i=' + Math.random(),
    success: function(data){
      if (data == 'NotLoggedIn') { window.location.replace('login.php'); }
    }
  });
}
function initSLData(userid) {
  var data = 'init=1&userID=' + urlencode(userid);
  $.ajax({
    type: 'POST',
    url: 'sharerlink_edit.php',
    data: data,
    success: function (html) {
      if (html == '0') {
        $('#sleconsole').html('You don\'t have any sharerlink added yet!');
        $('#editTitle').html('Add a Sharerlink');
        $('#updateBtn').html('Submit');
        $('#deleteBtn').hide();
        addLink = true;
      } else {
        initdata = html.split('[]');
        $('#inputSharername').val(initdata[0]);
        $('#inputSharerlink').val(initdata[1]);
        $('#inputSharerdesc').val(initdata[2]);
      }
    }
  });
}
function refreshList() {
  $("#sharerlist").html('<div class="loader" style="margin-top:10px"></div>');
  var data = 'display=1';
  $.ajax({
    type: 'POST',
    url: 'sharerlink.php',
    data: data,
    success: function (html) {
      $("#sharerlist").html(html);
    }
  });
}
</script>

<?php echo html('end'); ?>
