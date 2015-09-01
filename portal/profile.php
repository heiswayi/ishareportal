<?php

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {

    if (!defined('FORUM_ROOT')) {
        define('FORUM_ROOT', '../forum/');
    }
    if (!defined('SITE_ROOT')) {
        define('SITE_ROOT', './');
    }
    
    require_once(FORUM_ROOT . 'include/common.php'); // (required) // session_start()
    require_once(SITE_ROOT . 'include/html.php'); // HTML structures
    
    // Check current user session ID
    $currentUserID = $forum_user['id'];
    if ($forum_user['is_guest']) { header('Location: login.php'); }
    
    require_once(SITE_ROOT . 'portal_config.php');
    require_once(SITE_ROOT . 'include/functions.php');
    require_once(SITE_ROOT . 'include/database.class.php');
    
    function get_avatar($avatar_ext, $user_id)
    {
        if ($avatar_ext == '1') {
            return FORUM_ROOT . 'img/avatars/' . $user_id . '.gif?no_cache=' . random_keyx(8, TRUE);
        }
        if ($avatar_ext == '2') {
            return FORUM_ROOT . 'img/avatars/' . $user_id . '.jpg?no_cache=' . random_keyx(8, TRUE);
        }
        if ($avatar_ext == '3') {
            return FORUM_ROOT . 'img/avatars/' . $user_id . '.png?no_cache=' . random_keyx(8, TRUE);
        }
        if ($avatar_ext == '0') {
            return SITE_ROOT . 'assets/img/default-avatar.png';
        }
    }
    
    $db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    
    $userID = $db->prot(htmlspecialchars($_GET['id']));
    $checkDB = $db->num($db->query("SELECT * FROM forum_users WHERE id='$userID'"));
    if ($checkDB !== 1) { header('Location: index.php'); }
    
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
    

<div class="span3">
        
    <div class="user-topleft open-sans">
    <div>
    <?php
    
    $db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $query = $db->query("SELECT * FROM forum_users WHERE id='$userID'");
    $row = $db->fetch_array($query);
    $avatar = $row['avatar'];
    $realname = $row['realname'];
    $username = $row['username'];
    $lastvisit = $row['last_visit'];
    
    echo '<a href="profile.php?id='.$userID.'"><img class="avatar user-topleft-avatar" src="'.get_avatar($avatar,$userID).'"></a>';
    echo '<div class="user-topleft-info">';
    echo '<a href="profile.php?id='.$userID.'"><strong>';
    if (strlen($realname) > 0) { echo stripslashes(rtrim($realname)); }
    else { echo stripslashes(rtrim($username)); }
    echo '</strong></a>';
    echo '<br/><span style="font-size:11px;">Last Visit: '.date('d/m/Y g:i A', $lastvisit).'</span>';
    if ($currentUserID == $userID) {
      echo '<br/><a href="../forum/profile.php?section=identity&id='.$userID.'" style="font-size:11px;">Edit Profile</a>';
    } else {
      echo '<br/><a href="../forum/profile.php?id='.$userID.'" style="font-size:11px;">View Profile on Forum</a>';
    }
    echo '</div>';
    echo '</div>';
    
    
    $query1 = $db->query("SELECT id FROM ip_shouts WHERE user_id='$userID'");
    $query2 = $db->query("SELECT id FROM ip_updates WHERE user_id='$userID'");
    $query3 = $db->query("SELECT id FROM ip_requests WHERE user_id='$userID'");
    $total1 = $db->num($query1);
    $total2 = $db->num($query2);
    $total3 = $db->num($query3);
    echo '<div class="user-topleft-nav">';
    echo '<ul class="nav nav-list">';
    echo '<li><a href="profile.php?id='.$userID.'&sp=shouts" style="color:#333"><i class="icon-comments-alt muted"></i> User Messages <span class="badge pull-right" style="margin-top:1px;">'.$total1.'</span></a></li>';
    echo '<li><a href="profile.php?id='.$userID.'&sp=updates" style="color:#333"><i class="icon-list-alt muted"></i> User Updates <span class="badge pull-right" style="margin-top:1px;">'.$total2.'</span></a></li>';
    echo '<li><a href="profile.php?id='.$userID.'&sp=requests" style="color:#333"><i class="icon-list-alt muted"></i> User Requests <span class="badge pull-right" style="margin-top:1px;">'.$total3.'</span></a></li>';
    echo '</ul>';
    
    ?>
    </div>
    </div>
    
    <div class="left-menu">
    <ul class="nav nav-list">
      <li class="nav-header">Forum Quick Links</li>
      <li><a href="../forum/viewforum.php?id=9" target="_blank" class="tip-top" title="Post & Promote Your Ads"><i class="icon-shopping-cart"></i> Ishare Marketplace</a></li>
      <li><a href="../forum/misc.php?action=news" target="_blank" class="tip-top" title="News Posts from Forum Ishare"><i class="icon-globe"></i> News</a></li>
      <li><a href="../forum/viewforum.php?id=9" target="_blank" class="tip-top" title="HFS Templates - Sharing & Download"><i class="icon-download-alt"></i> HFS Templates</a></li>
      <li><a href="../forum/viewforum.php?id=8" target="_blank"><i class="icon-fire"></i> Events/Activities</a></li>
      <li class="divider"></li>
      <li><a href="http://irc.lc/W3C/ishare/anon@@@" target="_blank"><i class="icon-eye-open"></i> IshareAnon IRC Chatroom</a></li>
      <li><a href="../forum/viewtopic.php?id=6" target="_blank"><i class="icon-legal"></i> Legal Notice</a></li>
      <li><a href="http://www.facebook.com/groups/komuniti.ishare/" target="_blank"><i class="icon-facebook-sign"></i> Komuniti Ishare (FB Group)</a></li>
      <li><a href="../forum/viewforum.php?id=4" target="_blank"><i class="icon-question-sign"></i> Help &amp; Technical Support</a></li>
    </ul>
    </div>
    
</div>


    
<div class="span6">

<?php
    if ($forum_config['o_announcement'] == 1) {
        echo '<div class="alert alert-block"><h4>' . $forum_config['o_announcement_heading'] . '</h4>' . $forum_config['o_announcement_message'] . '</div>';
    }
?>
    
<!-- #shoutTextarea will be here soon -->

<?php

    if (isset($_GET['sp']) && $_GET['sp'] == 'shouts') {
    
    $dbp = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $querydbp = $dbp->query("SELECT * FROM ip_shouts WHERE user_id='$userID' ORDER BY id DESC");
    
    $checkSize = $dbp->num($querydbp);
    if ($checkSize == 0) {
      if ($currentUserID == $userID) { echo '<div class="alert alert-error">You don\'t have any shout message yet.</div>'; }
      else { echo '<div class="alert alert-error">This user doesn\'t shout anything yet.</div>'; }
    } else {
    
    echo '<ul id="chat" class="chat">';
    
    $count_shout = 0;
    
    while ($row = $dbp->fetch_assoc()) {
        $count_shout++;
        
        $get_shoutID  = $row['id'];
        $get_userID   = $row['user_id'];
        $get_shoutMsg = $row['shout_msg'];
        $get_sTime    = $row['shout_time'];
        
        $dbf = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $querydbf = $dbf->query("SELECT * FROM forum_users WHERE id='$get_userID'");
        
        if ($rowf = $dbf->fetch_array($querydbf)) {
            $get_groupID    = $rowf['group_id'];
            $get_username   = $rowf['username'];
            $get_realname   = $rowf['realname'];
            $get_title      = $rowf['title'];
            $get_location   = $rowf['location'];
            $get_registered = $rowf['registered'];
            $get_url        = $rowf['url'];
            $get_facebook   = $rowf['facebook'];
            $get_twitter    = $rowf['twitter'];
            $show_avatar    = $rowf['show_avatars'];
            $avatar_type    = $rowf['avatar'];
            
            if ($get_facebook == null) {
                $facebook_url = '';
            } else if ((strpos($get_facebook, "http://") === 0) || (strpos($get_facebook, "https://") === 0)) {
                $facebook_url = '<a href="' . $get_facebook . '">' . $get_facebook . '</a>';
            } else {
                $facebook_url = '<a href="http://facebook.com/' . $get_facebook . '">http://facebook.com/' . $get_facebook . '</a>';
            }
            
            if ($get_twitter == null) {
                $twitter_url = '';
            } else if ((strpos($get_twitter, "http://") === 0) || (strpos($get_twitter, "https://") === 0)) {
                $twitter_url = '<a href="' . $get_twitter . '">' . $get_twitter . '</a>';
            } else {
                $twitter_url = '<a href="http://twitter.com/' . $get_twitter . '">http://twitter.com/' . $get_twitter . '</a>';
            }
            
            if ($get_url == null) {
                $website = '';
            } else if ((strpos($get_url, "http://") === 0) || (strpos($get_url, "https://") === 0)) {
                $website = '<a href="' . $get_url . '">' . $get_url . '</a>';
            } else {
                $website = '<a href="http://' . $get_url . '">http://' . $get_url . '</a>';
            }
            
        }
        
        $dbf->close();
        
        if ($_SESSION['current_userID'] == $get_userID) {
            if ($count_shout == 20) {
                echo '<li id="lastShout" class="right">';
            } else {
                echo '<li class="right">';
            }
        } else {
            if ($count_shout == 20) {
                echo '<li id="lastShout" class="left">';
            } else {
                echo '<li class="left">';
            }
        }
        
        echo '<img class="avatar" alt="' . $get_username . '" src="' . get_avatar($avatar_type, $get_userID) . '">';
        
        echo '<span class="message"><span class="arrow"></span>';
        
        if ($get_realname == null) {
            echo '<span class="from"><strong>@' . $get_username . '</strong> ';
        } else {
            echo '<span class="from"><strong>' . $get_realname . '</strong> ';
        }
        
        if ($get_title !== null) {
            echo '<span class="forum-title"><em>' . $get_title . '</em></span></span> ';
        }
        
        echo '<span class="time muted"><small>' . timeAgo($get_sTime) . '</small></span>';
        
        echo '<span class="text" id="msg-' . $get_shoutID . '">' . stripslashes(rtrim(clickable(bbCode($get_shoutMsg)))) . '</span>';
        
        echo '</span></li>';
        
    }
    
    echo '</ul>';
    }
    $dbp->close();
    
    }
    else if (isset($_GET['sp']) && $_GET['sp'] == 'updates') {
    
    $dbu = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $querydbu = $dbu->query("SELECT * FROM ip_updates WHERE user_id='$userID' ORDER BY id DESC");
    
    $checkSize = $dbu->num($querydbu);
    if ($checkSize == 0) {
      if ($currentUserID == $userID) { echo '<div class="alert alert-error">You don\'t have any update message yet.</div>'; }
      else { echo '<div class="alert alert-error">This user doesn\'t update anything yet.</div>'; }
    } else {
    
    echo '<table id="update" class="table update-table"><tbody>';
    
    while ($row = $dbu->fetch_assoc($querydbu)) {
        $get_shoutID  = $row['id'];
        $get_userID   = $row['user_id'];
        $get_shoutMsg = $row['shout_msg'];
        $get_sTime    = $row['shout_time'];
        
        $dbf = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $querydbf = $dbf->query("SELECT * FROM forum_users WHERE id='$get_userID'");
        
        if ($rowf = $dbf->fetch_array($querydbf)) {
            $get_username = $rowf['username'];
            $get_realname = $rowf['realname'];
            $get_title    = $rowf['title'];
            $show_avatar  = $rowf['show_avatars'];
            $avatar_type  = $rowf['avatar'];
        }
        
        $dbf->close();
        
        echo '<tr id="' . $get_shoutID . '">';
        echo '<td class="user-avatar"><img alt="' . $get_username . '" src="' . get_avatar($avatar_type, $get_userID) . '"></td>';
        echo '<td class="chat-box"><div class="user-meta">';
        
        if ($get_realname == null) {
            echo '<strong>@' . $get_username . '</strong> ';
        } else {
            echo '<strong>' . $get_realname . '</strong> ';
        }
        
        if ($get_title !== null) {
            echo '<span class="forum-title"><em>' . $get_title . '</em></span> ';
        }
        
        echo '<span class="time muted"><small>' . timeAgo($get_sTime) . '</small></span>';
        
        echo '<span class="pull-right">';
        // Like button
        $dbll = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $dbll->query("SELECT COUNT(*) FROM ip_uplikes WHERE shout_id='$get_shoutID'");
        $total_like = implode($dbll->fetch_assoc());
        $dbll->close();
        $currentUserId = $_SESSION['current_userID'];
        if ($total_like > 0) {
            echo '<button id="btnLike likeThisID-' . $get_shoutID . '" class="btn btn-mini tip-top" title="People like this" onClick="likeThism(\'' . $get_shoutID . '\',\'' . $currentUserId . '\')"><i class="icon-thumbs-up"></i> <span id="uplikeTotalm-' . $get_shoutID . '">' . $total_like . '</span></button>';
        } else {
            echo '<button id="btnLike likeThisID-' . $get_shoutID . '" class="btn btn-mini tip-top" title="People like this" onClick="likeThism(\'' . $get_shoutID . '\',\'' . $currentUserId . '\')"><i class="icon-thumbs-up"></i> <span id="uplikeTotalm-' . $get_shoutID . '">0</span></button>';
        }
        // End of Like button
        echo '</span>';
        
        echo '</div><div class="user-msg">' . stripslashes(rtrim(clickable(bbCode($get_shoutMsg)))) . '</div></td>';
        echo '</tr>';
        
    }
    
    echo '</tbody></table><div class="close-table"></div>';
    }
    $dbu->close();
    
    echo '
    <script>
    $(document).ready(function () { // START DOCUMENT.READY
    
    $(".tip-top, .link-tip").tooltip();
    
    }); // END DCOUMENT.READY
    
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
    else if (isset($_GET['sp']) && $_GET['sp'] == 'requests') {
    
    $dbre = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $queryre = $dbre->query("SELECT * FROM ip_requests WHERE user_id='$userID' ORDER BY id DESC");
    
    $checkSize = $dbre->num($queryre);
    if ($checkSize == 0) {
      if ($currentUserID == $userID) { echo '<div class="alert alert-error">You don\'t have any request message yet.</div>'; }
      else { echo '<div class="alert alert-error">This user doesn\'t request anything yet.</div>'; }
    } else {
    
    echo '<table id="request" class="table request-table"><tbody>';
    
    while ($row = $dbre->fetch_assoc($queryre)) {
        $get_shoutID  = $row['id'];
        $get_userID   = $row['user_id'];
        $get_shoutMsg = $row['shout_msg'];
        $get_sTime    = $row['shout_time'];
        
        $dbf = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $querydbf = $dbf->query("SELECT * FROM forum_users WHERE id='$get_userID'");
        
        if ($rowf = $dbf->fetch_array($querydbf)) {
            $get_username = $rowf['username'];
            $get_realname = $rowf['realname'];
            $get_title    = $rowf['title'];
            $show_avatar  = $rowf['show_avatars'];
            $avatar_type  = $rowf['avatar'];
        }
        
        $dbf->close();
        
        echo '<tr id="' . $get_shoutID . '">';
        echo '<td class="user-avatar"><img alt="' . $get_username . '" src="' . get_avatar($avatar_type, $get_userID) . '"></td>';
        echo '<td class="chat-box"><div class="user-meta">';
        
        if ($get_realname == null) {
            echo '<strong>@' . $get_username . '</strong> ';
        } else {
            echo '<strong>' . $get_realname . '</strong> ';
        }
        
        if ($get_title !== null) {
            echo '<span class="forum-title"><em>' . $get_title . '</em></span> ';
        }
        
        echo '<span class="time muted"><small>' . timeAgo($get_sTime) . '</small></span>';
        
        echo '<button class="btn btn-mini btn-primary pull-right" id="reply-' . $get_shoutID . '" onClick="reply(\'' . $get_shoutID . '\');"><i class="icon-retweet icon-white"></i> REPLY</button>';
        
        echo '</div><div class="user-msg">' . stripslashes(rtrim(clickable(bbCode($get_shoutMsg)))) . '</div>';
        
        // Reply section
        $dbr = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $dbc = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $dbr->query("SELECT * FROM ip_reply WHERE request_id='$get_shoutID' ORDER BY id DESC LIMIT 10");
        $dbc->query("SELECT COUNT(id) FROM ip_reply WHERE request_id='$get_shoutID'");
        $rcount = implode($dbc->fetch_assoc());
        $dbc->close();
        if ($rcount > 0) {
            while ($rowr = $dbr->fetch_assoc()) {
                $replyID    = $rowr['id'];
                $replier_id = $rowr['replier_id'];
                $reply_msg  = $rowr['reply_msg'];
                $reply_time = $rowr['reply_time'];
                $dbff       = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
                $dbff->query("SELECT * FROM forum_users WHERE id='$replier_id'");
                if ($rowff = $dbff->fetch_array()) {
                    $replier_username = $rowff['username'];
                    $replier_realname = $rowff['realname'];
                }
                $dbf->close();
                if ($replier_realname == null) {
                    echo '<div id="userReply-' . $replyID . '" class="user-reply" title="' . timeAgo($reply_time) . '"><a href="' . FORUM_ROOT . 'profile.php?section=about&id=' . $replier_id . '" class="label label-inverse"><i class="icon-user icon-white"></i> @' . $replier_username . '</a> ' . stripslashes(rtrim(clickable(replybbCode($reply_msg)))) . '</div>';
                } else {
                    echo '<div id="userReply-' . $replyID . '" class="user-reply" title="' . timeAgo($reply_time) . '"><a href="' . FORUM_ROOT . 'profile.php?section=about&id=' . $replier_id . '" class="label label-inverse"><i class="icon-user icon-white"></i> ' . $replier_realname . '</a> ' . stripslashes(rtrim(clickable(replybbCode($reply_msg)))) . '</div>';
                }
                echo '<script>$("#userReply-' . $replyID . '").tooltip({placement: "right"});</script>';
            }
        } else {
            echo '<div class="user-reply">No reply yet.</div>';
        }
        $dbr->close();
        // End of Reply section
        
        echo '</td></tr>';
        
    }
    
    echo '</tbody></table><div class="close-table"></div>';
    }
    $dbre->close();
    
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
    
    function reply(msgid){ replyID = msgid; $("#replyForm").modal("show");}
    </script>
    ';
    
    }
    else {
    
?>

<div class="combo-section">
<ul class="nav nav-tabs combo-nav" id="threebox">
  <li class="active"><a href="#shoutbox"><i class="icon-comments-alt"></i> Posted Shouts</a></li>
  <li><a href="#updatebox"><i class="icon-list-alt"></i> Posted Updates</a></li>
  <li><a href="#requestbox"><i class="icon-list-alt"></i> Posted Requests</a></li>
</ul>
      
<div class="tab-content combo-content">

<div class="tab-pane active" id="shoutbox">

<?php
    $dbp = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $dbp->query("SELECT * FROM ip_shouts WHERE user_id='$userID' ORDER BY id DESC");
    
    $checkSize = $dbp->num($dbp->query("SELECT * FROM ip_shouts WHERE user_id='$userID' ORDER BY id DESC"));
    if ($checkSize == 0) {
      if ($currentUserID == $userID) { echo '<div class="alert alert-error">You don\'t have any shout message yet.</div>'; }
      else { echo '<div class="alert alert-error">This user doesn\'t shout anything yet.</div>'; }
    } else {
    
    echo '<ul id="chat" class="chat">';
    
    $count_shout = 0;
    
    while ($row = $dbp->fetch_assoc()) {
        $count_shout++;
        
        $get_shoutID  = $row['id'];
        $get_userID   = $row['user_id'];
        $get_shoutMsg = $row['shout_msg'];
        $get_sTime    = $row['shout_time'];
        
        $dbf = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $dbf->query("SELECT * FROM forum_users WHERE id='$get_userID'");
        
        if ($rowf = $dbf->fetch_array()) {
            $get_groupID    = $rowf['group_id'];
            $get_username   = $rowf['username'];
            $get_realname   = $rowf['realname'];
            $get_title      = $rowf['title'];
            $get_location   = $rowf['location'];
            $get_registered = $rowf['registered'];
            $get_url        = $rowf['url'];
            $get_facebook   = $rowf['facebook'];
            $get_twitter    = $rowf['twitter'];
            $show_avatar    = $rowf['show_avatars'];
            $avatar_type    = $rowf['avatar'];
            
            if ($get_facebook == null) {
                $facebook_url = '';
            } else if ((strpos($get_facebook, "http://") === 0) || (strpos($get_facebook, "https://") === 0)) {
                $facebook_url = '<a href="' . $get_facebook . '">' . $get_facebook . '</a>';
            } else {
                $facebook_url = '<a href="http://facebook.com/' . $get_facebook . '">http://facebook.com/' . $get_facebook . '</a>';
            }
            
            if ($get_twitter == null) {
                $twitter_url = '';
            } else if ((strpos($get_twitter, "http://") === 0) || (strpos($get_twitter, "https://") === 0)) {
                $twitter_url = '<a href="' . $get_twitter . '">' . $get_twitter . '</a>';
            } else {
                $twitter_url = '<a href="http://twitter.com/' . $get_twitter . '">http://twitter.com/' . $get_twitter . '</a>';
            }
            
            if ($get_url == null) {
                $website = '';
            } else if ((strpos($get_url, "http://") === 0) || (strpos($get_url, "https://") === 0)) {
                $website = '<a href="' . $get_url . '">' . $get_url . '</a>';
            } else {
                $website = '<a href="http://' . $get_url . '">http://' . $get_url . '</a>';
            }
            
        }
        
        $dbf->close();
        
        if ($_SESSION['current_userID'] == $get_userID) {
            if ($count_shout == 20) {
                echo '<li id="lastShout" class="right">';
            } else {
                echo '<li class="right">';
            }
        } else {
            if ($count_shout == 20) {
                echo '<li id="lastShout" class="left">';
            } else {
                echo '<li class="left">';
            }
        }
        
        echo '<img class="avatar" alt="' . $get_username . '" src="' . get_avatar($avatar_type, $get_userID) . '">';
        
        echo '<span class="message"><span class="arrow"></span>';
        
        if ($get_realname == null) {
            echo '<span class="from"><strong>@' . $get_username . '</strong> ';
        } else {
            echo '<span class="from"><strong>' . $get_realname . '</strong> ';
        }
        
        if ($get_title !== null) {
            echo '<span class="forum-title"><em>' . $get_title . '</em></span></span> ';
        }
        
        echo '<span class="time muted"><small>' . timeAgo($get_sTime) . '</small></span>';
        
        echo '<span class="text" id="msg-' . $get_shoutID . '">' . stripslashes(rtrim(clickable(bbCode($get_shoutMsg)))) . '</span>';
        
        echo '</span></li>';
        
    }
    
    echo '</ul>';
    }
    $dbp->close();
    
?>

</div><!-- /#shoutbox -->
        
<div class="tab-pane" id="updatebox">

<?php
    
    $dbu = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $dbu->query("SELECT * FROM ip_updates WHERE user_id='$userID' ORDER BY id DESC");
    
    $checkSize = $dbp->num($dbu->query("SELECT * FROM ip_updates WHERE user_id='$userID' ORDER BY id DESC"));
    if ($checkSize == 0) {
      if ($currentUserID == $userID) { echo '<div class="alert alert-error">You don\'t have any update message yet.</div>'; }
      else { echo '<div class="alert alert-error">This user doesn\'t update anything yet.</div>'; }
    } else {
    
    echo '<table id="update" class="table update-table"><tbody>';
    
    while ($row = $dbu->fetch_assoc()) {
        $get_shoutID  = $row['id'];
        $get_userID   = $row['user_id'];
        $get_shoutMsg = $row['shout_msg'];
        $get_sTime    = $row['shout_time'];
        
        $dbf = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $dbf->query("SELECT * FROM forum_users WHERE id='$get_userID'");
        
        if ($rowf = $dbf->fetch_array()) {
            $get_username = $rowf['username'];
            $get_realname = $rowf['realname'];
            $get_title    = $rowf['title'];
            $show_avatar  = $rowf['show_avatars'];
            $avatar_type  = $rowf['avatar'];
        }
        
        $dbf->close();
        
        echo '<tr id="' . $get_shoutID . '">';
        echo '<td class="user-avatar"><img alt="' . $get_username . '" src="' . get_avatar($avatar_type, $get_userID) . '"></td>';
        echo '<td class="chat-box"><div class="user-meta">';
        
        if ($get_realname == null) {
            echo '<strong>@' . $get_username . '</strong> ';
        } else {
            echo '<strong>' . $get_realname . '</strong> ';
        }
        
        if ($get_title !== null) {
            echo '<span class="forum-title"><em>' . $get_title . '</em></span> ';
        }
        
        echo '<span class="time muted"><small>' . timeAgo($get_sTime) . '</small></span>';
        
        echo '<span class="pull-right">';
        // Like button
        $dbll = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $dbll->query("SELECT COUNT(*) FROM ip_uplikes WHERE shout_id='$get_shoutID'");
        $total_like = implode($dbll->fetch_assoc());
        $dbll->close();
        $currentUserId = $_SESSION['current_userID'];
        if ($total_like > 0) {
            echo '<button id="btnLike likeThisID-' . $get_shoutID . '" class="btn btn-mini tip-top" title="People like this" onClick="likeThism(\'' . $get_shoutID . '\',\'' . $currentUserId . '\')"><i class="icon-thumbs-up"></i> <span id="uplikeTotalm-' . $get_shoutID . '">' . $total_like . '</span></button>';
        } else {
            echo '<button id="btnLike likeThisID-' . $get_shoutID . '" class="btn btn-mini tip-top" title="People like this" onClick="likeThism(\'' . $get_shoutID . '\',\'' . $currentUserId . '\')"><i class="icon-thumbs-up"></i> <span id="uplikeTotalm-' . $get_shoutID . '">0</span></button>';
        }
        // End of Like button
        echo '</span>';
        
        echo '</div><div class="user-msg">' . stripslashes(rtrim(clickable(bbCode($get_shoutMsg)))) . '</div></td>';
        echo '</tr>';
        
    }
    
    echo '</tbody></table><div class="close-table"></div>';
    }
    $dbu->close();
    
    echo '
    <script>
    $(document).ready(function () { // START DOCUMENT.READY
    
    $(".tip-top, .link-tip").tooltip();
    
    }); // END DCOUMENT.READY
    
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
    
?>

</div><!-- /#updatebox -->
        
<div class="tab-pane" id="requestbox">

<?php
    
    $dbre = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $dbre->query("SELECT * FROM ip_requests WHERE user_id='$userID' ORDER BY id DESC");
    
    $checkSize = $dbp->num($dbre->query("SELECT * FROM ip_requests WHERE user_id='$userID' ORDER BY id DESC"));
    if ($checkSize == 0) {
      if ($currentUserID == $userID) { echo '<div class="alert alert-error">You don\'t have any request message yet.</div>'; }
      else { echo '<div class="alert alert-error">This user doesn\'t request anything yet.</div>'; }
    } else {
    
    echo '<table id="request" class="table request-table"><tbody>';
    
    while ($row = $dbre->fetch_assoc()) {
        $get_shoutID  = $row['id'];
        $get_userID   = $row['user_id'];
        $get_shoutMsg = $row['shout_msg'];
        $get_sTime    = $row['shout_time'];
        
        $dbf = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $dbf->query("SELECT * FROM forum_users WHERE id='$get_userID'");
        
        if ($rowf = $dbf->fetch_array()) {
            $get_username = $rowf['username'];
            $get_realname = $rowf['realname'];
            $get_title    = $rowf['title'];
            $show_avatar  = $rowf['show_avatars'];
            $avatar_type  = $rowf['avatar'];
        }
        
        $dbf->close();
        
        echo '<tr id="' . $get_shoutID . '">';
        echo '<td class="user-avatar"><img alt="' . $get_username . '" src="' . get_avatar($avatar_type, $get_userID) . '"></td>';
        echo '<td class="chat-box"><div class="user-meta">';
        
        if ($get_realname == null) {
            echo '<strong>@' . $get_username . '</strong> ';
        } else {
            echo '<strong>' . $get_realname . '</strong> ';
        }
        
        if ($get_title !== null) {
            echo '<span class="forum-title"><em>' . $get_title . '</em></span> ';
        }
        
        echo '<span class="time muted"><small>' . timeAgo($get_sTime) . '</small></span>';
        
        echo '<button class="btn btn-mini btn-primary pull-right" id="reply-' . $get_shoutID . '" onClick="reply(\'' . $get_shoutID . '\');"><i class="icon-retweet icon-white"></i> REPLY</button>';
        
        echo '</div><div class="user-msg">' . stripslashes(rtrim(clickable(bbCode($get_shoutMsg)))) . '</div>';
        
        // Reply section
        $dbr = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $dbc = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $dbr->query("SELECT * FROM ip_reply WHERE request_id='$get_shoutID' ORDER BY id DESC LIMIT 10");
        $dbc->query("SELECT COUNT(id) FROM ip_reply WHERE request_id='$get_shoutID'");
        $rcount = implode($dbc->fetch_assoc());
        $dbc->close();
        if ($rcount > 0) {
            while ($rowr = $dbr->fetch_assoc()) {
                $replyID    = $rowr['id'];
                $replier_id = $rowr['replier_id'];
                $reply_msg  = $rowr['reply_msg'];
                $reply_time = $rowr['reply_time'];
                $dbff       = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
                $dbff->query("SELECT * FROM forum_users WHERE id='$replier_id'");
                if ($rowff = $dbff->fetch_array()) {
                    $replier_username = $rowff['username'];
                    $replier_realname = $rowff['realname'];
                }
                $dbf->close();
                if ($replier_realname == null) {
                    echo '<div id="userReply-' . $replyID . '" class="user-reply" title="' . timeAgo($reply_time) . '"><a href="' . FORUM_ROOT . 'profile.php?section=about&id=' . $replier_id . '" class="label label-inverse"><i class="icon-user icon-white"></i> @' . $replier_username . '</a> ' . stripslashes(rtrim(clickable(replybbCode($reply_msg)))) . '</div>';
                } else {
                    echo '<div id="userReply-' . $replyID . '" class="user-reply" title="' . timeAgo($reply_time) . '"><a href="' . FORUM_ROOT . 'profile.php?section=about&id=' . $replier_id . '" class="label label-inverse"><i class="icon-user icon-white"></i> ' . $replier_realname . '</a> ' . stripslashes(rtrim(clickable(replybbCode($reply_msg)))) . '</div>';
                }
                echo '<script>$("#userReply-' . $replyID . '").tooltip({placement: "right"});</script>';
            }
        } else {
            echo '<div class="user-reply">No reply yet.</div>';
        }
        $dbr->close();
        // End of Reply section
        
        echo '</td></tr>';
        
    }
    
    echo '</tbody></table><div class="close-table"></div>';
    }
    $dbre->close();
    
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
    
    function reply(msgid){ replyID = msgid; $("#replyForm").modal("show");}
    </script>
    ';
    
?>

</div><!-- /#requestbox -->
        
</div><!-- /.combo-content -->     
</div><!-- /.combo-section -->

<?php } ?>
      
</div>    

<?php
    include(SITE_ROOT . 'side_right.php');
?>
    
</div><!-- /.row-fluid -->

</div><!-- /.container -->
    
<?php
    html_footer();
?>

<div id="ipStats" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="myModalLabel">IsharePortal Statistics</h3>
  </div>
  <div class="modal-body">
<div class="stat-data">
<?php
    require_once(SITE_ROOT . 'portal_config.php');
    require_once(SITE_ROOT . 'include/database.class.php');
    $db            = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $t_users       = $db->num($db->query("SELECT id FROM forum_users")) - 1;
    $t_shouts      = $db->num($db->query("SELECT id FROM ip_shouts"));
    $t_updates     = $db->num($db->query("SELECT id FROM ip_updates"));
    $t_requests    = $db->num($db->query("SELECT id FROM ip_requests"));
    $t_reply       = $db->num($db->query("SELECT id FROM ip_reply"));
    $t_sharerlinks = $db->num($db->query("SELECT id FROM ip_sharerlinks"));
    echo 'Total of registered users: <strong>' . $t_users . ' usernames</strong><br/>';
    echo 'Total of current shouts: <strong>' . $t_shouts . ' messages</strong><br/>';
    echo 'Total of <code>!update</code> shouts: <strong>' . $t_updates . ' messages</strong><br/>';
    echo 'Total of <code>!request</code> shouts: <strong>' . $t_requests . ' messages</strong><br/>';
    echo 'Total of replies: <strong>' . $t_reply . ' messages</strong><br/>';
    echo 'Total of sharerlinks: <strong>' . $t_sharerlinks . ' links</strong><br/>';
    $top5 = $db->query("SELECT user_id, COUNT(user_id) AS top5 FROM ip_shouts GROUP BY user_id ORDER BY top5 DESC LIMIT 5");
    $rank = 1;
    echo 'Top 5 Shouters:<br/>';
    while ($row = $db->fetch_assoc($top5)) {
        $userIDx   = $row['user_id'];
        $username = implode($db->fetch_assoc($db->query("SELECT username FROM forum_users WHERE id='$userIDx'")));
        $shoutno  = $db->num($db->query("SELECT shout_msg FROM ip_shouts WHERE user_id='$userIDx'"));
        echo '<span class="label label-inverse tip-top" title="' . $shoutno . ' shouts"><span class="icon-red">' . $rank . '</span> ' . $username . '</span> ';
        $rank++;
    }
    echo '<br/>';
    $top3  = $db->query("SELECT sharer_id, COUNT(sharer_id) AS top3 FROM ip_shlikes GROUP BY sharer_id ORDER BY top3 DESC LIMIT 3");
    $srank = 1;
    echo 'Top 3 Most-liked Sharerlinks:<br/>';
    while ($row = $db->fetch_assoc($top3)) {
        $sharerID   = $row['sharer_id'];
        $sharername = implode($db->fetch_assoc($db->query("SELECT sharername FROM ip_sharerlinks WHERE id='$sharerID'")));
        $shlikes    = $db->num($db->query("SELECT id FROM ip_shlikes WHERE sharer_id='$sharerID'"));
        echo '<span class="label label-inverse tip-top" title="' . $shlikes . ' likes"><span class="icon-red">' . $srank . '</span> ' . $sharername . '</span> ';
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

<?php
    html_script();
?>
<script>
var checkSessionTimer = setTimeout("checkSession()", 1000);

// Shoutbox tab, Updates tab & Requests tab
$('#threebox a[href="#shoutbox"]').click(function(e){e.preventDefault();$('#threebox a[href="#shoutbox"]').tab('show');});
$('#threebox a[href="#updatebox"]').click(function(e){e.preventDefault();$('#threebox a[href="#updatebox"]').tab('show');});
$('#threebox a[href="#requestbox"]').click(function(e){e.preventDefault();$('#threebox a[href="#requestbox"]').tab('show');});

function checkSession(){
  clearTimeout(checkSessionTimer);
  $.ajax({
    type: 'GET', url: 'session.php?check=1&i=' + Math.random(),
    success: function(data){
      if (data == 'NotLoggedIn') { window.location.replace('login.php'); }
      else { checkSessionTimer = setTimeout("checkSession()", 1000); }
    }
  });
}
</script>

<?php
    echo html('end');
?>

<?php
} else {
    header('Location: index.php');
}
?>
