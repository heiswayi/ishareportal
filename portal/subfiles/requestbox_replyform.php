<?php

if (session_id() == '') { session_start(); }
$currentUserID = $_SESSION['current_userID'];

if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', '../'); }

require_once(SITE_ROOT.'portal_config.php');    
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');

function get_avatar($avatar_ext, $user_id) {
  if ($avatar_ext == '1') { return FORUM_ROOT.'img/avatars/'.$user_id.'.gif'; }
  if ($avatar_ext == '2') { return FORUM_ROOT.'img/avatars/'.$user_id.'.jpg'; }
  if ($avatar_ext == '3') { return FORUM_ROOT.'img/avatars/'.$user_id.'.png'; }
  if ($avatar_ext == '0') { return SITE_ROOT.'portal/assets/img/default-avatar.png'; }
}

if (isset($_GET['rid']) && !empty($_GET['rid']) && is_numeric($_GET['rid'])) {

$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$requestID = $db->prot(htmlspecialchars($_GET['rid']));
$db->query("SELECT * FROM ip_requests WHERE id='$requestID'");

echo '<div class="latest-shouts">';
echo '<i class="icon-edit"></i> Write a reply to this request';
echo '<ul id="rmsg" class="chat">';
  
if ($row=$db->fetch_array()) {

  $reqmsg = $row['shout_msg'];
  $reqmsgid = $row['id'];
  $reqmsgtime = $row['shout_time'];
  $requser = $row['user_id'];
  
  $dbf = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $dbf->query("SELECT * FROM forum_users WHERE id='$requser'");
  
  if ($rowf=$dbf->fetch_array()) {
    $get_username = $rowf['username'];
    $get_realname = $rowf['realname'];
    $get_title = $rowf['title'];
    $show_avatar = $rowf['show_avatars'];
    $avatar_type = $rowf['avatar'];
  }
  
  $dbf->close();
  
  echo '<li id="'.$reqmsgid.'" class="left">';
  echo '<img class="avatar" alt="'.$get_username.'" src="'.get_avatar($avatar_type, $requser).'">';
  echo '<span class="message"><span class="arrow"></span>';
  if ($get_realname == null) { echo '<span class="from"><strong>@'.$get_username.'</strong> '; }
  else { echo '<span class="from"><strong>'.$get_realname.'</strong> '; }
  echo '<span class="time muted"><small>'.timeAgo($reqmsgtime).'</small></span>';  
  echo '<span class="text" id="msg-'.$reqmsgid.'">'.stripslashes(rtrim(clickable(bbCode($reqmsg)))).'</span>';
  echo '</span></li>';

}

$db->close();
echo '</ul>';
echo '</div>';

echo '
<div id="rconsole" class="alert alert-error" style="display:none"></div>

<form id="shoutForm" class="form-horizontal">
   
<textarea id="replyTextarea" class="input-block-level msg-box" placeholder="Type your message here" rows="3" maxlength="500"></textarea>
<input type="hidden" id="replyUserId" value="'.$currentUserID.'">
<input type="hidden" id="requestMsgId" value="'.$reqmsgid.'">
      
<div class="post-control" style="min-height:30px">
<label class="checkbox inline span4">
  <input id="showReplyMsg" type="checkbox" checked="checked"> Post on Shoutbox
</label>

<div class="btn-group pull-right">
  <button class="btn btn-info" id="rrButton" title="Disclaimer"><i class="icon-info-sign icon-white"></i></button>
  <button class="btn btn-emoticon" title="Basic Smileys" type="button" id="basicSmileys"><i class="launcher basic-smileys"></i></button>
  <button id="replyButton" class="btn btn-success"><i class="icon-pencil icon-white"></i> POST</button>
  <button id="clearBtnReply" class="btn btn-warning" title="Clear"><i class="icon-trash"></i></button>
</div>
</div><!-- /.post-control -->
     
<div id="basicSmileysDiv" class="rbc" style="display:none;border:1px solid #ddd;border-top:none;padding:5px;">
<img src="assets/img/smileys/blush.png" onclick="insertBSmiley(\'[;blush;]\')" title="[;blush;]" /> 
<img src="assets/img/smileys/broken-heart.png" onclick="insertBSmiley(\'[;broken-heart;]\')" title="[;broken-heart;]" /> 
<img src="assets/img/smileys/confuse.png" onclick="insertBSmiley(\'[;confuse;]\')" title="[;confuse;]" /> 
<img src="assets/img/smileys/cool.png" onclick="insertBSmiley(\'[;cool;]\')" title="[;cool;]" /> 
<img src="assets/img/smileys/cry.png" onclick="insertBSmiley(\'[;cry;]\')" title="[;cry;]" /> 
<img src="assets/img/smileys/eek.png" onclick="insertBSmiley(\'[;eek;]\')" title="[;eek;]" /> 
<img src="assets/img/smileys/evil.png" onclick="insertBSmiley(\'[;evil;]\')" title="[;evil;]" />
<img src="assets/img/smileys/fat.png" onclick="insertBSmiley(\'[;fat;]\')" title="[;fat;]" /> 
<img src="assets/img/smileys/green.png" onclick="insertBSmiley(\'[;green;]\')" title="[;green;]" /> 
<img src="assets/img/smileys/grin.png" onclick="insertBSmiley(\'[;grin;]\')" title="[;grin;]" /> 
<img src="assets/img/smileys/happy.png" onclick="insertBSmiley(\'[;happy;]\')" title="[;happy;]" /> 
<img src="assets/img/smileys/heart.png" onclick="insertBSmiley(\'[;heart;]\')" title="[;heart;]" /> 
<img src="assets/img/smileys/kiss.png" onclick="insertBSmiley(\'[;kiss;]\')" title="[;kiss;]" /> 
<img src="assets/img/smileys/kitty.png" onclick="insertBSmiley(\'[;kitty;]\')" title="[;kitty;]" /> 
<img src="assets/img/smileys/lol.png" onclick="insertBSmiley(\'[;lol;]\')" title="[;lol;]" /> 
<img src="assets/img/smileys/mad.png" onclick="insertBSmiley(\'[;mad;]\')" title="[;mad;]" /> 
<img src="assets/img/smileys/money.png" onclick="insertBSmiley(\'[;money;]\')" title="[;money;]" /> 
<img src="assets/img/smileys/neutral.png" onclick="insertBSmiley(\'[;neutral;]\')" title="[;neutral;]" /> 
<img src="assets/img/smileys/razz.png" onclick="insertBSmiley(\'[;razz;]\')" title="[;razz;]" /> 
<img src="assets/img/smileys/roll.png" onclick="insertBSmiley(\'[;roll;]\')" title="[;roll;]" /> 
<img src="assets/img/smileys/sad.png" onclick="insertBSmiley(\'[;sad;]\')" title="[;sad;]" /> 
<img src="assets/img/smileys/sleep.png" onclick="insertBSmiley(\'[;sleep;]\')" title="[;sleep;]" /> 
<img src="assets/img/smileys/surprise.png" onclick="insertBSmiley(\'[;surprise;]\')" title="[;surprise;]" /> 
<img src="assets/img/smileys/wink.png" onclick="insertBSmiley(\'[;wink;]\')" title="[;wink;]" /> 
<img src="assets/img/smileys/yell.png" onclick="insertBSmiley(\'[;yell;]\')" title="[;yell;]" /> 
<img src="assets/img/smileys/zipper.png" onclick="insertBSmiley(\'[;zipper;]\')" title="[;zipper;]" /> 
<img src="assets/img/smileys/like.png" onclick="insertBSmiley(\'[;like;]\')" title="[;like;]" /> 
<img src="assets/img/smileys/dislike.png" onclick="insertBSmiley(\'[;dislike;]\')" title="[;dislike;]" />
</div>

<div id="replyRules" style="display:none;border:1px solid #ddd;border-top:none;padding:5px;color:#777;">
By replying to this user\'s request, you agreed that; all of your contents from every single word and the link(s) provided are under your own responsibilities. Ishare will not be responsible for any consequence.
</div>

</form>

<script>
$(document).ready(function () { // START DOCUMENT.READY

$("#basicSmileys, #basicSmileysDiv img, #clearButton, #rrButton").tooltip();
$("#basicSmileys").click(function (e) { e.preventDefault(); $("#basicSmileysDiv").slideToggle(); });
$("#rrButton").click(function (e) { e.preventDefault(); $("#replyRules").slideToggle(); });
$("#clearBtnReply").click(function (e) { e.preventDefault(); $("#replyTextarea").val(""); });
$("#replyButton").click(function(){
    if ($("#showReplyMsg").is(":checked")) { var cts = 1; }
    else { var cts = 0; }
    var requestMsgID = $("#requestMsgId").val();
    var replyMsg = $("#replyTextarea").val();
    var userID = $("#replyUserId").val();
    var dataString = "requestMsgID=" + urlencode(requestMsgID) + "&replyMsg=" + urlencode(replyMsg) + "&userID=" + urlencode(userID) + "&cts=" + urlencode(cts);
    $.ajax({
      type: "POST", url: "subfiles/requestbox_reply.php", data: dataString,
      success: function(html){
          if (html == "OK") { $("#replyTextarea").val(""); refreshRequestMsg(); }
          else { $("#rconsole").html(html).fadeIn().delay(3000).fadeOut(); }
      }
    });
    return false;
});

}); // END DCOUMENT.READY

function urlencode(a) {
  a = (a + "").toString();
  return encodeURIComponent(a).replace(/!/g, "%21").replace(/\'/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\*/g, "%2A").replace(/%20/g, "+")
}
function refreshRequestMsg(){
    $("#requestbox").html("<div class=\"loader\" style=\"margin-top:100px\"></div>");
    var data = "display=1";  
    $.ajax({
          type: "POST", url: "requestbox_display.php", data: data,
          success: function(html){
              $("#requestbox").html(html).fadeIn();
              $("#replyForm").modal("hide");
              $(".modal-backdrop").fadeOut("fast");
          }
    });
};
function insertBSmiley(smiley){
  var currentText = document.getElementById("replyTextarea");
  var smileyWithPadding = " " + smiley + " ";
  currentText.value += smileyWithPadding;
}
</script>
';

}

?>