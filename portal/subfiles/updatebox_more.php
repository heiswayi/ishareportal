<?php

error_reporting(E_ALL);

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
    $db->query("SELECT * FROM ip_updates ORDER BY id DESC LIMIT $start, $per_page");
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
      
      $msg .= '<span class="pull-right">';
      // Like button
      $dbll = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
      $dbll->query("SELECT COUNT(*) FROM ip_uplikes WHERE shout_id='$get_shoutID'");
      $total_like = implode($dbll->fetch_assoc());
      $dbll->close();
      $currentUserId = $_SESSION['current_userID'];
      if ($total_like > 0) {
        $msg .= '<button id="btnLike likeThisID-'.$get_shoutID.'" class="btn btn-mini tip-top" title="People like this" onClick="likeThism(\''.$get_shoutID.'\',\''.$currentUserId.'\')"><i class="icon-thumbs-up"></i> <span id="uplikeTotalm-'.$get_shoutID.'">'.$total_like.'</span></button>';
      } else {
        $msg .= '<button id="btnLike likeThisID-'.$get_shoutID.'" class="btn btn-mini tip-top" title="People like this" onClick="likeThism(\''.$get_shoutID.'\',\''.$currentUserId.'\')"><i class="icon-thumbs-up"></i> <span id="uplikeTotalm-'.$get_shoutID.'">0</span></button>';
      }
      // End of Like button
      $msg .= ' <button class="btn btn-mini tip-top" id="rtupdate-'.$get_shoutID.'" onClick="rtupdate(\''.$get_shoutID.'\',\''.$get_username.'\');" title="Reshout"><i class="icon-share"></i> RT</button> ';
      $msg .= '</span>';
      
      $msg .= '</div><div class="user-msg">'.stripslashes(rtrim(clickable(bbCode($get_shoutMsg)))).'</div></td>';
      $msg .= '</tr>';

  }
  $db->close();
  
  // Content for Sharer's Updates
  $msg = '<table id="update" class="table update-table"><tbody>'.$msg.'</tbody></table><div class="close-table"></div>';
  
  $dbt = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $dbt->query("SELECT COUNT(id) FROM ip_updates");
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
    <script>
    $(document).ready(function () { // START DOCUMENT.READY
    
    $(".tip-top, .link-tip").tooltip();
    
    }); // END DCOUMENT.READY
    
    function urlencode(a) {
      a = (a + "").toString();
      return encodeURIComponent(a).replace(/!/g, "%21").replace(/\'/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\*/g, "%2A").replace(/%20/g, "+")
    }
    function rtupdatem(msgid,user){      
      $.ajax({
        type: "GET", url: "subfiles/updatebox_retweet.php?retweet=" + urlencode(msgid),
        success: function(html){
          if (html !== "KO") { $("#shoutTextarea").val("RT @"+user+": " + html); }
        }
      });
    }
    function likeThism(shoutID,curUserID){
      var dataString = "sid=" + urlencode(shoutID) + "&lid=" + urlencode(curUserID);
      $.ajax({
        type: "POST", url: "subfiles/updatebox_like.php", data: dataString,
        success: function(html){
          $("#uplikeTotalm-" + shoutID).html(html);
        }
      });
    }
    </script>
    ';
  
}
  
?>