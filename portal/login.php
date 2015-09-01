<?php

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }
require(SITE_ROOT.'include/html.php'); // HTML structures

// Load top level HTML structures
html('start');
html_meta();
html_css();
html_favicon();
html_jquery();
html('body');

?>

<div id="loginText" class="login-text">
<img id="loginLogo" src="assets/img/logo.png">
<p class="open-sans">Welcome to Ishare Forum-integrated Portal 2013 <a href="#ishareInfo" data-toggle="modal" title="What's this?"><i class="icon-question-sign semi-transparent"></i></a></p>
<a href="../forum/login.php" class="btn btn-primary">Login</a> <a href="../forum/register.php" class="btn btn-success">Register</a>
</div>

<div id="ishareInfo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-body open-sans" style="font-size:13px;line-height:16px;">
    <div class="to-know">
    <div style="padding:10px;text-align:right;">
    <h3>So, you really want to know huh?<br />It's a long story! Seriously.</h3>
    <a href="whatisthis.php" class="btn btn-success">Yerp, I don't mind!</a>
    </div>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">No!</button>
  </div>
</div>

<?php html_script(); ?>

<?php echo html('end'); ?>
