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

<div class="container">
<div style="margin:50px auto;">
<a href="index.php" title="Back to Portal"><img id="loginLogo" src="assets/img/logo.png"></a>
<div class="open-sans" style="margin-top:30px;font-size:16px;line-height:24px;">
<p><a href="index.php">IsharePortal</a> is the <em>third generation</em> of Ishare webpages, integrated with forum user account system (<a href="../forum">Forum Ishare</a>). IsharePortal is totally new different experience which is mostly developed and coded with PHP/MySQL and jQuery AJAX from a stratch. IsharePortal represents a bunch of new improvements especially to the shoutbox (core component), sharerlink (core component), sharer's updates section and user's requests section.</p>

<p>So, why third generation? OK, the original purpose of Ishare, which founded by Heiswayi Nrird on February 2010 is <strong>to gather all sharerlinks in one place so that the sharerlinks are easily found by the users and provides a communicating space for community (Komuniti Ishare) interactions</strong>. <em>First generation</em> of Ishare was developed by using WordPress platform with Add Link plugin for sharerlink and using a free external shoutbox service for community interaction medium.</p>

<p>For <em>second generation</em> of Ishare, after Ishare has been transferred from external hosting to internal hosting (within campus network), the big upgrades have been done to the site whereas it was redeveloped based on PHP/MySQL with own PHP/AJAX shoutbox and improved sharerlink indicator system. During this generation, from the last version of its development and improvements, Onion Club and Tuzki Club emoticons have been introduced.</p>

<p>And now, <strong>IsharePortal</strong> - the new generation (3rd generation) of Ishare once again has been upgraded and redeveloped from a stratch based on PHP/MySQL and jQuery AJAX. It's simple and dynamic. The difference between previous generation and this generation is about almost of the components were coded or using jQuery AJAX, apparently the new shoutbox system which is using modern codes (jQuery AJAX) while the previous one used old-fashioned AJAX.</p>

<p>From the first until now, still Ishare has been continuously maintained by Heiswayi Nrird (Founder &amp; Core Developer) and assisted by Pirates_Killer as Ishare Co-Founder &amp; SuperAdmin with a big supports from <strong>Komuniti Ishare</strong>. However, this generation is the last generation of Ishare that has been maintained by Heiswayi Nrird and after this, Pirates_Killer will be take over the Ishare.</p>

<p>For more informations, take a look on <a href="http://mpp.eng.usm.my/sharers/forum/viewforum.php?id=3">Forum Ishare -> Ishare Development Center</a>.</p>
</div>
</div>
</div>

<?php html_script(); ?>

<?php echo html('end'); ?>
