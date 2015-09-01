<?php

if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }
    
require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');

if (isset($_GET['display']) && $_GET['display'] == 1) { populate_updatebox(); }
else { header('Location: '.SITE_ROOT.'404.php'); }

function populate_updatebox() {

    $db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $db->query("SELECT COUNT(id) FROM ip_updates"); 
    $total_update = implode($db->fetch_assoc());
    $db->close();
    
    echo '<div class="alert alert-info">This is <strong>Sharer\'s Updates</strong> section (currently contained <strong>'.$total_update.'</strong> update shouts). Just use <code>!update</code> code in your shout to make them appear here.</div>';
    
    echo '<div id="containerx">';
    echo '<div class="data"></div>';
    echo '<div class="pagination"></div>';
    echo '</div>';
    
    echo '
    <script>
    $(document).ready(function () { // START DOCUMENT.READY
    
    $(".tip-top, .link-tip").tooltip();
    
    function loadData(page){
      $("#containerx").html("<div class=\"loader\" style=\"margin-top:10px\"></div>").fadeIn("fast");
      $.ajax({
        type: "GET",
        url: "subfiles/updatebox_more.php?page="+page,
        success: function(msg){
          $("#containerx").html(msg);
        }
      });
    }
    loadData(1);  // For first time page load default results
    $("#containerx .pagination li.enx").live("click",function(e){
      e.preventDefault();
      var page = $(this).attr("p");
      loadData(page);
    });           
    
    }); // END DCOUMENT.READY

    </script>
    ';
}
?>