<?php

if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }

require_once(FORUM_ROOT.'include/common.php'); // (required) // session_start()
require_once(SITE_ROOT.'include/html.php'); // HTML structures

// Check current user session ID
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

<div class="hero-unit">
  <h2>Hello, <?php echo $forum_user['username']; ?>!</h2>
  <p>Since this is a forum-integrated portal whereas we used your account from the forum, so you may edit your profile over there!</p>
  <p>
    <a href="../forum/profile.php?section=identity&id=<?php echo $forum_user['id']; ?>" class="btn btn-primary btn-large">
      Click here to edit your profile
    </a>
  </p>
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
var checkUserSession = setInterval(checkSession, 5000);
function checkSession(){
  $.ajax({
    type: 'GET', url: 'session.php?check=1&i=' + Math.random(),
    success: function(data){
      if (data == 'NotLoggedIn') { window.location.replace('login.php'); }
    }
  });
}
</script>

<?php echo html('end'); ?>
