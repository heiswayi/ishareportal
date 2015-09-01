<div class="span3">
    
    <div class="open-sans" style="text-align:center;font-size:11px;line-height:13px;margin-bottom:20px;background:#000;color:#ccc;">
    <img src="assets/ico/apple-touch-icon-144-precomposed.png">
    <div style="padding:5px;">Welcome to <strong>IsharePortal</strong>, a forum-integrated portal for all students in Engineering Campus, USM.</div>
    </div>
    
    <div class="user-topleft open-sans">
    <div>
    <?php
    
    require_once(SITE_ROOT.'portal_config.php');
    require_once(SITE_ROOT.'include/functions.php');
    require_once(SITE_ROOT.'include/database.class.php');

    function get_avatar($avatar_ext, $user_id) {
      if ($avatar_ext == '1') { return FORUM_ROOT.'img/avatars/'.$user_id.'.gif?no_cache='.random_keyx(8, TRUE); }
      if ($avatar_ext == '2') { return FORUM_ROOT.'img/avatars/'.$user_id.'.jpg?no_cache='.random_keyx(8, TRUE); }
      if ($avatar_ext == '3') { return FORUM_ROOT.'img/avatars/'.$user_id.'.png?no_cache='.random_keyx(8, TRUE); }
      if ($avatar_ext == '0') { return SITE_ROOT.'assets/img/default-avatar.png'; }
    }
    
    echo '<a href="profile.php?id='.$forum_user['id'].'"><img class="avatar user-topleft-avatar" src="'.get_avatar($forum_user['avatar'],$forum_user['id']).'"></a>';
    echo '<div class="user-topleft-info">';
    echo '<a href="profile.php?id='.$forum_user['id'].'"><strong>';
    if (strlen($forum_user['realname']) > 0) { echo stripslashes(rtrim($forum_user['realname'])); }
    else { echo stripslashes(rtrim($forum_user['username'])); }
    echo '</strong></a>';
    echo '<br/><span style="font-size:11px;">Last Visit: '.date('d/m/Y g:i A', $forum_user['last_visit']).'</span>';
    echo '<br/><a href="../forum/profile.php?section=identity&id='.$forum_user['id'].'" style="font-size:11px;">Edit Profile</a>';
    echo '</div>';
    echo '</div>';
    
    $db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $user_id = $forum_user['id'];
    $query1 = $db->query("SELECT id FROM ip_shouts WHERE user_id='$user_id'");
    $query2 = $db->query("SELECT id FROM ip_updates WHERE user_id='$user_id'");
    $query3 = $db->query("SELECT id FROM ip_requests WHERE user_id='$user_id'");
    $total1 = $db->num($query1);
    $total2 = $db->num($query2);
    $total3 = $db->num($query3);
    echo '<div class="user-topleft-nav">';
    echo '<ul class="nav nav-list">';
    echo '<li><a href="profile.php?id='.$forum_user['id'].'&sp=shouts" style="color:#333"><i class="icon-comments-alt muted"></i> My Messages <span class="badge pull-right" style="margin-top:1px;">'.$total1.'</span></a></li>';
    echo '<li><a href="profile.php?id='.$forum_user['id'].'&sp=updates" style="color:#333"><i class="icon-list-alt muted"></i> My Updates <span class="badge pull-right" style="margin-top:1px;">'.$total2.'</span></a></li>';
    echo '<li><a href="profile.php?id='.$forum_user['id'].'&sp=requests" style="color:#333"><i class="icon-list-alt muted"></i> My Requests <span class="badge pull-right" style="margin-top:1px;">'.$total3.'</span></a></li>';
    echo '</ul>';
    
    ?>
    </div>
    </div>
    
    <div class="left-menu">
    <ul class="nav nav-list">
      <li class="nav-header">Forum Quick Links</li>
      <li><a href="../forum/viewforum.php?id=7" target="_blank" class="tip-top" title="Post & Promote Your Ads"><i class="icon-shopping-cart"></i> Ishare Marketplace</a></li>
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
    
    <div class="forum-threads">
    <div class="forum-threads-header"><i class="icon-book"></i> Forum Recent Topics</div>
    <?php include('subfiles/forum_recent_posts.php'); ?>
    </div>
    
    <div class="short-note" style="border:1px solid #ccc;padding:10px;">
    If you found any bug, just <a href="../forum/viewforum.php?id=4">report here</a>, when we're free, we'll fix it. To follow the development details, you can take a look on <a href="../forum/viewtopic.php?id=9">IsharePortal Devlog</a>.
    </div>
    
</div>