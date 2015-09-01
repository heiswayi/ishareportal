<?php

if (session_id() == '') { session_start(); }

if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', '../'); }
    
require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');

function get_avatar($avatar_ext, $user_id) {
  if ($avatar_ext == '1') { return FORUM_ROOT.'img/avatars/'.$user_id.'.gif?no_cache='.random_keyx(8, TRUE); }
  if ($avatar_ext == '2') { return FORUM_ROOT.'img/avatars/'.$user_id.'.jpg?no_cache='.random_keyx(8, TRUE); }
  if ($avatar_ext == '3') { return FORUM_ROOT.'img/avatars/'.$user_id.'.png?no_cache='.random_keyx(8, TRUE); }
  if ($avatar_ext == '0') { return SITE_ROOT.'portal/assets/img/default-avatar.png'; }
}

if (isset($_GET['page'])) {

    $page = $_GET['page'];
    $cur_page = $page;
    $page -= 1;
    $per_page = 15;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;
    
    $db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $db->query("SELECT * FROM ip_requests ORDER BY id DESC LIMIT $start, $per_page");
    $msg = '';
    while($row=$db->fetch_assoc()) {
    
      $get_shoutID = $row['id'];
      $get_userID =  $row['user_id'];
      $get_shoutMsg = $row['shout_msg'];
      $get_sTime = $row['shout_time'];
    
      $dbf = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
      $dbf->query("SELECT * FROM forum_users WHERE id='$get_userID'");
    
      if ($rowf=$dbf->fetch_array()) {
        $get_username = $rowf['username'];
        $get_realname = $rowf['realname'];
        $get_title = $rowf['title'];
        $show_avatar = $rowf['show_avatars'];
        $avatar_type = $rowf['avatar'];
      }
      
      $dbf->close();
      
      $msg .= '<tr id="'.$get_shoutID.'">';
      $msg .= '<td class="user-avatar"><a href="profile.php?id='.$get_userID.'"><img alt="'.$get_username.'" src="'.get_avatar($avatar_type, $get_userID).'"></a></td>';
      $msg .= '<td class="chat-box"><div class="user-meta">';
      
      if ($get_realname == null) {
        $msg .= '<a href="profile.php?id='.$get_userID.'" class="user-name">@'.$get_username.'</a> ';
      } else {
        $msg .= '<a href="profile.php?id='.$get_userID.'" class="user-name">'.$get_realname.'</a> ';
      }
      
      if ($get_title !== null) {
        $msg .= '<span class="forum-title"><em>'.$get_title.'</em></span> ';
      }
      
      $msg .= '<span class="time muted"><small>'.timeAgo($get_sTime).'</small></span>';
      
      $msg .= '<button class="btn btn-mini btn-primary pull-right" id="reply-'.$get_shoutID.'" onClick="reply(\''.$get_shoutID.'\');"><i class="icon-retweet icon-white"></i> REPLY</button>';
      
      $msg .= '</div><div class="user-msg">'.stripslashes(rtrim(clickable(bbCode($get_shoutMsg)))).'</div>';
      
      // Reply section
      $dbr = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
      $dbc = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
      $dbr->query("SELECT * FROM ip_reply WHERE request_id='$get_shoutID' ORDER BY id DESC LIMIT 5");
      $dbc->query("SELECT COUNT(id) FROM ip_reply WHERE request_id='$get_shoutID'");
      $rcount = implode($dbc->fetch_assoc());
      $dbc->close();
      if ($rcount > 0) {
        while($rowr=$dbr->fetch_assoc()) {
          $replyID = $rowr['id'];
          $replier_id = $rowr['replier_id'];
          $reply_msg = $rowr['reply_msg'];
          $reply_time = $rowr['reply_time'];
          $dbff = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
          $dbff->query("SELECT * FROM forum_users WHERE id='$replier_id'");
          if ($rowff=$dbff->fetch_array()) {
            $replier_username = $rowff['username'];
            $replier_realname = $rowff['realname'];
          }
          $dbf->close();
          if ($replier_realname == null) {
            $msg .= '<div id="userReply-'.$replyID.'" class="user-reply" title="'.timeAgo($reply_time).'"><a href="'.FORUM_ROOT.'profile.php?section=about&id='.$replier_id.'" class="label label-inverse"><i class="icon-user icon-white"></i> @'.$replier_username.'</a> '.stripslashes(rtrim(clickable(replybbCode($reply_msg)))).'</div>';
          } else {
            $msg .= '<div id="userReply-'.$replyID.'" class="user-reply" title="'.timeAgo($reply_time).'"><a href="'.FORUM_ROOT.'profile.php?section=about&id='.$replier_id.'" class="label label-inverse"><i class="icon-user icon-white"></i> '.$replier_realname.'</a> '.stripslashes(rtrim(clickable(replybbCode($reply_msg)))).'</div>';
          }
          $msg .= '<script>$("#userReply-'.$replyID.'").tooltip({placement: "right"});</script>';
        }
      } else { $msg .= '<div class="user-reply">No reply yet.</div>'; }
      $dbr->close();
      // End of Reply section
      
      $msg .= '</td></tr>';

  }
  
  $db->close();
  
  // Content for Sharer's Updates
  $msg = '<table id="request" class="table request-table"><tbody>'.$msg.'</tbody></table><div class="close-table"></div>';
  
  $dbt = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $dbt->query("SELECT COUNT(id) FROM ip_requests");
  $count = implode($dbt->fetch_assoc());
  $dbt->close();
  
  $no_of_paginations = ceil($count / $per_page);
  
  if ($cur_page >= 7) {
    $start_loop = $cur_page - 3;
    if ($no_of_paginations > $cur_page + 3)
        $end_loop = $cur_page + 3;
    else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
        $start_loop = $no_of_paginations - 6;
        $end_loop = $no_of_paginations;
    } else {
        $end_loop = $no_of_paginations;
    }
  } else {
    $start_loop = 1;
    if ($no_of_paginations > 7)
        $end_loop = 7;
    else
        $end_loop = $no_of_paginations;
  }
  
  
  $msg .= "<div class='pagination pagination-centered'><ul>";

  // FOR ENABLING THE FIRST BUTTON
  if ($first_btn && $cur_page > 1) {
    $msg .= "<li p='1' class='enx'><a href='#'>Newest</a></li>";
  } else if ($first_btn) {
    $msg .= "<li p='1' class='disabled'><a href='#'>Newest</a></li>";
  }

  // FOR ENABLING THE PREVIOUS BUTTON
  if ($previous_btn && $cur_page > 1) {
    $pre = $cur_page - 1;
    $msg .= "<li p='$pre' class='enx'><a href='#'>Newer</a></li>";
  } else if ($previous_btn) {
    $msg .= "<li class='disabled'><a href='#'>Newer</a></li>";
  }
  for ($i = $start_loop; $i <= $end_loop; $i++) {
    if ($cur_page == $i)
        $msg .= "<li p='$i' class='enx active'><a href='#'>{$i}</a></li>";
    else
        $msg .= "<li p='$i' class='enx'><a href='#'>{$i}</a></li>";
  }

  // TO ENABLE THE NEXT BUTTON
  if ($next_btn && $cur_page < $no_of_paginations) {
    $nex = $cur_page + 1;
    $msg .= "<li p='$nex' class='enx'><a href='#'>Older</a></li>";
  } else if ($next_btn) {
    $msg .= "<li class='disabled'><a href='#'>Older</a></li>";
  }

  // TO ENABLE THE END BUTTON
  if ($last_btn && $cur_page < $no_of_paginations) {
    $msg .= "<li p='$no_of_paginations' class='enx'><a href='#'>Oldest</a></li>";
  } else if ($last_btn) {
    $msg .= "<li p='$no_of_paginations' class='disabled'><a href='#'>Oldest</a></li>";
  }
  
  $msg = $msg . "</ul></div>";  // Content for Pagination
  
  echo $msg;
  
  echo '
      <div id="replyForm" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header hn-color-blue">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel">Reply</h3>
      </div>
      <div id="reply" class="modal-body">
      </div>
      </div>
  ';
  
  echo '
    <script>
    var replyID;
    
    $(document).ready(function () { // START DOCUMENT.READY
    
    $(".tip-top").tooltip();
    $("#replyForm").on("shown", function () { $("#reply").load("subfiles/requestbox_replyform.php?rid=" + urlencode(replyID)); });
    
    }); // END DCOUMENT.READY
    
    function urlencode(a) {
      a = (a + "").toString();
      return encodeURIComponent(a).replace(/!/g, "%21").replace(/\'/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\*/g, "%2A").replace(/%20/g, "+")
    }
    function reply(msgid){ replyID = msgid; $("#replyForm").modal("show");}
    </script>
    ';
  
}
  
?>