<?php
define('ASSETS_FOLDER', 'assets/');

function html($level){
if ($level == 'start') { echo '<!DOCTYPE html><html lang="en"><head>'; }
if ($level == 'body') { echo '</head><body>'; }
if ($level == 'end') { echo '</body></html>'; }
}

function html_meta($title = 'IsharePortal', $desc = '', $author = 'Heiswayi Nrird'){
echo '
<title>'.$title.'</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="'.$desc.'">
<meta name="author" content="'.$author.'">
';
}

define('CSS_VERSION', '28012013'); // <-- DATE DDMMYYYY
function html_css(){
echo '
<link href="'.ASSETS_FOLDER.'css/ishare-no-icons.css?v='.CSS_VERSION.'" rel="stylesheet">
<link href="'.ASSETS_FOLDER.'css/ishare-responsive.css?v='.CSS_VERSION.'" rel="stylesheet">
<link href="'.ASSETS_FOLDER.'css/ishare-override.css?v='.CSS_VERSION.'" rel="stylesheet">
<link href="'.ASSETS_FOLDER.'css/ishare-custom.css?v='.CSS_VERSION.'" rel="stylesheet">
<link href="'.ASSETS_FOLDER.'css/font-awesome.min.css" rel="stylesheet">
<!--[if IE 7]>
<link href="'.ASSETS_FOLDER.'css/font-awesome-ie7.min.css" rel="stylesheet">
<![endif]-->
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
';
}

function html_favicon(){
echo '
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="'.ASSETS_FOLDER.'ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="'.ASSETS_FOLDER.'ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="'.ASSETS_FOLDER.'ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="'.ASSETS_FOLDER.'ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="'.ASSETS_FOLDER.'ico/favicon.png">
';
}

function html_jquery(){
echo '
<script src="'.ASSETS_FOLDER.'js/jquery.js"></script>
<script src="'.ASSETS_FOLDER.'js/ishare.min.js"></script>
';
}

function html_header(){
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }
require(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/database.class.php');
$currentUserID = $_SESSION['current_userID'];
$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$db->query("SELECT * FROM forum_users WHERE id='$currentUserID'");
if ($row=$db->fetch_array()) {
  $username = $row['username'];
  $realname = $row['realname'];
}
$db->close();
if ($realname !== null) { $displayname = $realname; }
else { $displayname = $username; }
    
echo '
<div id="wrap">
<div class="navbar navbar-top"><div class="navbar-inner"><div class="container">
<a class="brand" href="#index.php"><div class="logo-ip"></div></a>
<div class="btn-group pull-left">
<a href="../forum" class="btn btn-inverse"><i class="icon-rss"></i> Forum Ishare</a>
</div>

<div class="btn-group">
  <button class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-list muted"></i> KampusLinks <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li><a href="http://mpp.eng.usm.my/">MPPUSMKKj Official Blog</a></li>
    <li><a href="http://hepp.eng.usm.my/">BHEPP USMKKj</a></li>
    <li><a href="http://infodesk.eng.usm.my/">Infodesk PPKT USMKKj</a></li>
    <li><a href="http://www.eng.usm.my/php/blockedIP/">Blocked Port List</a></li>
    <li><a href="http://elearning.usm.my/">e-Learning Portal</a></li>
    <li><a href="http://campusonline.usm.my/">CampusOnline Portal</a></li>
    <li><a href="http://www.tcom.usm.my/">Sistem Direktori Telefon USM</a></li>
    <li><a href="http://www.facebook.com/ppkt.eng.usm">Facebook PPKT USMKKj</a></li>
    <li class="divider"></li>
    <li><a href="http://hik3.net/refcode"><i class="icon-bookmark"></i> RefCode (Snippets)</a></li>
  </ul>
</div>
            
<div class="btn-group pull-right">
<a href="index.php" class="btn btn-primary"><i class="icon-home icon-white"></i> Home</a>
<a href="profile.php?id='.$currentUserID.'" class="btn btn-inverse"><i class="icon-user"></i> '.$displayname.'</a>
<button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li><a href="edit_profile.php"><i class="icon-edit muted"></i> Edit Profile</a></li>
    <li><a href="edit_sharerlink.php"><i class="icon-hdd muted"></i> Edit Sharerlink</a></li>
    <li class="divider"></li>
    <li><a href="../forum/login.php?action=out&id='.$currentUserID.'"><i class="icon-off muted"></i> Logout</a></li>
  </ul>
</div>
            
</div></div></div>
';
}

function html_footer(){
echo '
</div>
<footer><div class="container">
<div class="copyright-top"><a class="brand" href="#"><div class="logo-ip logo-footer pull-left"></div></a>
<a href="whatisthis.php" class="btn">About Ishare</a>
<a href="http://mpp.eng.usm.my/sharers/forum" class="btn"><i class="icon-rss"></i> Forum Ishare</a>
<a href="../forum/viewtopic.php?id=6" class="btn"><i class="icon-legal"></i> Legal Notice</a>
</div>
<div class="copyright-bottom">
&copy; Ishare, February 2010 - 2013 &#8226; Designed and built with all the <span style="color:#c00">L<i class="icon-heart-empty"></i>VE</span> in the campus by <a href="http://mpp.eng.usm.my/sharers/forum/profile.php?id=2">Heiswayi Nrird</a> &#8226; All rights reserved.
<a href="#top" class="pull-right tip-top" title="Back to Top">Top</a>
</div>
</div></footer>
';
}

function html_script(){
echo '
<script src="'.ASSETS_FOLDER.'js/autogrow.js"></script>
<script src="'.ASSETS_FOLDER.'js/idle.js"></script>
<script src="'.ASSETS_FOLDER.'js/custom.js"></script> 
<script src="'.ASSETS_FOLDER.'js/jquery.NobleCount.js"></script>
<script src="'.ASSETS_FOLDER.'js/bbcode-wrap.js"></script>
';
}
?>